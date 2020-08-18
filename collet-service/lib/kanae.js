/*!
 * kanae
 * Copyright(c) 2015 yutao <yut19856@126.com>
 * MIT Licensed
 */

/**
 * Module dependencies.
 */
var fs = require('fs');
var path = require('path');
var application = require('./application');

/**
 * Expose `createApplication()`.
 *
 * @module
 */

var Kanae = module.exports = {};

/**
 * Framework version.
 */
Kanae.version = '1.0.0';

var self = this;

/**
 * Create an Kanae application.
 *
 * @return {Application}
 * @memberOf Kanae
 * @api public
 */
Kanae.createApp = function (opts) {
    var app = application;
    app.init(opts);
    self.app = app;
    return app;
};

/**
 * Get application
 */
Object.defineProperty(Kanae, 'app', {
    get: function () {
        return self.app;
    }
});

function load(path, name) {
    if (name) {
        return require(path + name);
    }
    return require(path);
}
