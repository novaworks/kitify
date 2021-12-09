'use strict';

const { __, _x, _n, _nx, sprintf } = wp.i18n;

window.KitifyDashboardEventBus = new Vue();

window.KitifyDashboard = new KitifyDashboardClass();

window.KitifyDashboard.initVueComponents();

window.KitifyDashboardPageInstance = KitifyDashboard.initDashboardPageInstance();