var Service = require('node-windows').Service;
var log = require('./service-logger').log;

// Create a new service object
var svc = new Service({
  name:'PDF Download Server',
  script: require('path').join(__dirname,'app.js')
});


// Listen for the "uninstall" event so we know when it's done.
svc.on('uninstall',function(){
  console.log('Uninstall complete.');
  console.log('The service exists: ',svc.exists);
  log.info('PDF Download Service uninstalled');
});

// Uninstall the service.
svc.uninstall();