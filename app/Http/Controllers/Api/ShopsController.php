<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Shops;
use App\Models\GoodsCategory;
use App\Models\Goods;
use App\Models\GoodsSpecs;
use App\Models\Setting;
use DB;
use Illuminate\Support\Facades\Redis;
use Validator;

class ShopsController extends BaseController {

    protected $model = null;
    protected $setting = null;

    public function __construct(Request $request, Shops $model, Setting $setting) {
        $this->model = $model;
        $this->setting = $setting;
        parent::__construct($request);
    }

    /**
     * 获取店铺首页信息，店铺设置等
     * @param Request $request
     */
    public function index(Request $request) {
        $shops = $this->model->getList([
            'state' => 1,
            'deleted' => 0
            ], TRUE, 0, [
            'id', 'shop_name', 'image', 'phone', 'shop_address', 'long', 'lat', 'notice'
        ]);

        //设置分组
        $settings = $this->setting->whereExtend([
                'shop_id' => ['conn' => '>=', 'value' => 0]
            ])->select('name', 'value', 'shop_id')->get();
        
        $set = [];
        foreach ($settings as $item) {
            $set[$item->shop_id][$item->name] = $item->value;
        }
        

        //桌位分组
        $deskModel = new \App\Models\Desks;
        $desks = $deskModel->where('state', 1)->select('id', 'alias', 'number', 'shop_id')->get();
        $desk = [];
        foreach ($desks as $item) {
            $desk[$item->shop_id][] = $item;
        }

        //推荐食物
        //查询推荐的店铺
        $goodsShopsModel = new \App\Models\GoodsShops;
        $items = $goodsShopsModel->join('goods', 'goods.id', '=', 'goods_shops.goods_id')
            ->where('is_shelves', 1)
            ->where('state', 1)
            ->where('recommend', 1)
            ->groupBy('shop_id')
            ->select('shop_id', \DB::raw("group_concat(goods.id) as ids"))
            ->get();

        $goods_id = [0];
        $shop_goods_map = [];
        $length = 10;
        foreach ($items as $item) {
            $ids = explode(',', $item->ids);
            $shop_goods_map[$item->shop_id] = array_slice($ids, 0, $length);
            $goods_id = array_merge($goods_id, $shop_goods_map[$item->shop_id]);
        }
        $goodsModel = new Goods;
        $goods_data = $goodsModel->select('id', 'goods_name', 'sale', 'img', 'desc')
            ->where('is_shelves', 1)
            ->where('state', 1)
            ->where('recommend', 1)
            ->whereIn('id', $goods_id)
            ->get();
        $goods = [];
        foreach ($goods_data as $item) {
            $item->img = asset($item->img);
            foreach ($shop_goods_map as $shop_id => $ids) {
                if (in_array($item->id, $ids)) {
                    $goods[$shop_id][] = $item;
                }
            }
        }

        //查询广告
        $adsMode = new \App\Models\Ads;
        $ads = $adsMode->whereExtend([
                'state' => 1,
            ])->select('id', 'name', 'url', 'content', 'thumb')->limit(5)->orderBy('order', 'asc')->orderBy('id', 'desc')->get();

        foreach ($ads as &$ad) {
            $ad->thumb = asset($ad->thumb);
        }

        //查询新闻
        $newsMode = new \App\Models\News();
        $news_data = $newsMode->whereExtend([
                'state' => 1,
                'recommend' => 1
            ])->select('id', 'title', 'hits', 'thumb', 'description', 'recommend')
            ->limit(10)
            ->orderBy('id', 'desc')
            ->get();
        foreach ($news_data as &$news) {
            $news->thumb = asset($news->thumb);
            $news->uri = url('web.news.detail', ['id' => $news->id]);
        }
        
        foreach ($shops as &$shop) {
            $shop->image = asset($shop->image);
            $set[$shop->id]['takeout_price'] = $set[0]['takeout_price'];
            $set[$shop->id]['takeout_tableware_price'] = $set[0]['takeout_tableware_price'];
            $set[$shop->id]['takeout_distance'] = $set[0]['takeout_distance'];
            $shop->setting = $set[$shop->id];
            $shop->recommendFoods = isset($goods[$shop->id]) ? $goods[$shop->id] : [];
            $shop->deskList = isset($desk[$shop->id]) ? $desk[$shop->id] : [];
        }

        $this->back['data'] = [
            'shopList' => $shops,
            'adList' => $ads,
            'newsList' => $news_data,
        ];

        return $this->back;
    }

    /**
     * 获取店铺详细内容
     * @param Request $request
     * @param type $id
     */
    public function view(Request $request, $shop_id) {
        $shop = $this->model
            ->select('id', 'shop_name', 'image', 'phone', 'shop_address', 'long', 'lat', 'notice')
            ->where('deleted', 0)
            ->where('id', (int) $shop_id)
            ->where('state', 1)
            ->first();

        if (!$shop) {
            $this->back['status'] = '404';
            $this->back['msg'] = '店铺不存在';
            return $this->dataToJson($this->back);
        }
        $shop->image = asset($shop->image);
        //设置分组
        $set = $this->setting->whereExtend([
                'shop_id' => $shop->id
            ])->pluck('value', 'name')->toArray();

        $shop->setting = $set;

        //类型
        $typeModel = new \App\Models\QueueType;
        $types = $typeModel->where('shop_id', $shop->id)
            ->where('state', 1)
            ->select('id', 'name', 'shop_id', 'prefix', 'desc', 'average_time')
            ->get();



        //桌位分组
        $deskModel = new \App\Models\Desks;
        $desks = $deskModel->where('shop_id', $shop->id)
            ->where('state', 1)
            ->select('id', 'alias', 'number', 'shop_id', 'type_id')
            ->get();
        $groupDesk = [];
        foreach ($desks as $desk) {
            $groupDesk[$desk->type_id][] = $desk;
        }

        foreach ($types as &$type) {
            $type->desks = isset($groupDesk[$type->id]) ? $groupDesk[$type->id] : [];
        }
        $shop->deskTypes = $types;

        $this->back['data'] = $shop;
        return $this->back;
    }

    public function goods(Request $request) {
        $shop_id = $request->input('shop_id', 0);
        $shop = $this->model->select('id', 'shop_name', 'image', 'phone', 'shop_address', 'long', 'lat', 'notice')
            ->where('deleted', 0)
            ->where('id', (int) $shop_id)
            ->where('state', 1)
            ->first();

        if (!$shop) {
            $this->back['status'] = '404';
            $this->back['msg'] = '店铺未找到。';
            return $this->dataToJson($this->back);
        }

        //获取分类数据
        $categoryModel = new GoodsCategory();
        $cateArr = $categoryModel->getList([
            'state' => 1,
            'order' => ['field' => 'sort', 'sort' => 'asc']
            ], true, 0, [
            'id',
            'name',
            'sort'
        ]);

        //获取用户商品
        $goodsModel = new Goods();

        $goodsObj = $goodsModel->getGoodsByShopId($shop_id);
        $goodsArr = [];
        foreach ($goodsObj as $goods) {
            $goods->img = asset($goods->img);
            $goodsArr[$goods->id] = $goods;
        }
        
        //查询商品规格
        $specsModel = new GoodsSpecs();
        $specsArr = $specsModel->getSpecsByGoodsIds(array_keys($goodsArr));
        
        $cateGoods = [
            'recommend' => [], //推荐
            'hot' => [], //热销
        ];
        $hotNum = 0;
        foreach ($goodsArr as $key => $good) {
            $good->specs = isset($specsArr[$key]) ? $specsArr[$key] : [];
            //推荐全部
            if ($good->recommend > 0) {
                $cateGoods['recommend'][] = $good;
            }
            //热销5个
            if ($hotNum < 5) {
                $cateGoods['hot'][] = $good;
            }
            $cateGoods[$good->category_id][] = $good;
            
            $hotNum++;
        }
        
        //数据处理
        $data = [];
        //推荐商品处理
        if (isset($cateGoods['recommend'])) {
            $data[] = [
                'id' => 'recommend',
                'name' => '推荐',
                'sort' => '0',
                'goodsList' => $cateGoods['recommend'],
            ];
        }

        //热销商品处理
        if (isset($cateGoods['hot'])) {
            $data[] = [
                'id' => 'hot',
                'name' => '热销',
                'sort' => '0',
                'goodsList' => $cateGoods['hot'],
            ];
        }

        //循环自然分类
        foreach ($cateArr as $cateItem) {
            if (isset($cateGoods[$cateItem->id])) {
                //赋值goodslist到分类数据中
                $cateItem->goodsList = $cateGoods[$cateItem->id];
                //push数据到data中
                $data[] = $cateItem;
            }
        }

        //优惠券
        $activityModel = new \App\Models\Activity;
        $activity = $activityModel->getActivityByShopId($shop->id);

        $this->back['data'] = [
            'categoryList' => $data,
            'activityList' => $activity
        ];

        return $this->back;
    }

}
