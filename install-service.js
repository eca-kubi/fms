var Service = require('node-windows').Service;
var log = require('./service-logger').log;

// Create a new service object
var svc = new Service({
    name: 'PDF Download Server',
    description: 'This service starts a node js server running of port 3000, which handles PDF downloading for clients',
    script: 'C:\\xampp\\htdocs\\fms\\app.js'
});

// Listen for the "install" event, which indicates the
// process is available as a service.
svc.on('install', function () {
    svc.start();
});

svc.on('start', function (params) {
    log.info('Service started successfully')
});

svc.on('stop', function (params) {
    log.info('Service stopped')
});

svc.on('error', function (params) {
    log.warn('Service encountered an error')
});


// Listen for the "start" event and let us know when the
// process has actually started working.
svc.on('start', function () {
    let message = svc.name + ' started!\nVisit http://local.arlgh.com:3000 to see it in action.'
    console.log(message);
    log.info(message)
});

// Install the script as a service.
svc.install();