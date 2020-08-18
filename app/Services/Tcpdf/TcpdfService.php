<?php

namespace App\Services\Tcpdf;

use TCPDF;

define('K_PATH_IMAGES', public_path('tcpdf' . DIRECTORY_SEPARATOR . 'images') . DIRECTORY_SEPARATOR);
define('K_PATH_FONTS', public_path('tcpdf' . DIRECTORY_SEPARATOR . 'fonts') . DIRECTORY_SEPARATOR);

class TcpdfService extends TCPDF
{

    public static $service = null;

    public function __construct(
        $author = "findjapanjob",
        $title = "findjapanjob履歴書・職務経歴書PDF出力",
        $subject = "履歴書・職務経歴書",
        $keywords = "findjapanjob,履歴書,職務経歴書,PDF出力"
    ) {
        parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor($author);
        $this->SetTitle($title);
        $this->SetSubject($subject);
        $this->SetKeywords($keywords);

        // remove default header/footer
        $this->setPrintHeader(true);
        $this->SetHeaderMargin(5);
        $this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, 'B', 12));
//        $this->SetHeaderData("logo.png", 25, "", '', array(83,176,174), array(255,255,255));

        $this->setPrintFooter(true);
        // set default header data
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set default monospaced font
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $this->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
        // set auto page brea
        $this->SetAutoPageBreak(true, 15);

        // set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $this->top_margin = $this->GetY() + 5;

        // set some language-dependent strings (optional)
        $l = [];
        $l['a_meta_charset'] = 'UTF-8';
        $l['a_meta_dir'] = 'ltr';
        $l['a_meta_language'] = 'ja';
        $l['w_page'] = 'ページ';
        $this->setLanguageArray($l);

        //微软雅黑字体
        $this->SetFont('msyh', '', 10);
        $this->SetTextColor(75, 75, 75);
        $this->SetFillColor(255, 255, 255);
        $this->AddPage();
    }

    /**
     * 获取一个pdf obj对象
     * @param string $author
     * @param string $title
     * @param string $subject
     * @param string $keywords
     * @return TcpdfService|null
     */
    public static function getService(
        $author = "findjapanjob",
        $title = "findjapanjob履歴書・職務経歴書PDF出力",
        $subject = "履歴書・職務経歴書",
        $keywords = "findjapanjob,履歴書,職務経歴書,PDF出力"
    ) {
        if (self::$service) {
            return self::$service;
        }
        self::$service = new self($author, $title, $subject, $keywords);
        return self::$service;
    }

    // Page footer
    public function Footer()
    {
        return ;
        // Position at 15 mm from bottom
        $this->SetY(-12);
        $this->SetTextColor(159, 159, 159);
        // Page number
        $this->Cell(120, 9, $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R');
    }

    function setHeader($title = '')
    {
        $this->SetHeaderData($this->checkimg('/images/logo.png'), 25, $title, '', array(83, 176, 174),
            array(255, 255, 255));
    }

    function checkimg($img)
    {
        $path = public_path() . DIRECTORY_SEPARATOR . $img;
        if (is_file($img) && file_exists($img)) {
            return $img;
        } else {
            if (is_file($path) && file_exists($path)) {
                return $path;
            }
        }
        return false;
    }

    public function addSubTitle($title, $tln = 5, $bln = 3)
    {
        $this->Ln($tln);
//        $this->SetLineWidth(1);
        $this->SetFontSize(14);
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(29, 57, 139);
        $border = array(
            'B' => array(
                'width' => 0.7,
                'cap' => 'butt',
                'join' => 'miter',
                'dash' => 0,
                'color' => [29, 57, 139]
            )
        );
        $this->Cell(0, 1, $title, $border, 1, 'L', 1, '', 1, false, 'T', 'C');
        $this->SetFontSize(10);
        $this->SetTextColor(75, 75, 75);
        $this->Ln($bln);
    }

    public function addItemTable($title, $items, $tWidth = 30, $lineColor = [230, 230, 230])
    {
//        [242, 242, 242]  [29, 57, 139]
        $iWidth = 100-$tWidth;
        $this->Ln(0.5);
        $html = <<<EOF
<table style="width: 100%;" cellpadding="10">
				<tr>
					<td style="width: {$tWidth}%; background-color: #f2f2f2;">
					<div style="padding: 5px;height: 100%；width:100%">{$title}</div>
					</td>
					<td style="width: {$iWidth}%;margin: 8px;">{$items}</td>
				</tr>
			</table>
<!--			<div style="width: 100%;line-height: 5px"></div>-->
EOF;
        $border = array('B' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $lineColor));
        $this->writeHTMLCell(0, 1, $this->GetX(), $this->GetY(), $html, $border,0.5);
        $this->Ln(1);
    }

    public function addContnet($contnet)
    {
        $contnet = nl2br(trim($contnet));
        $html = <<<EOF
<div style="line-height: 18px">{$contnet}</div>
EOF;
        $this->writeHTML($html);
//        $this->Ln(10);

    }
}
