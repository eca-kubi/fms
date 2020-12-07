var EventLogger = require('node-windows').EventLogger;

exports.log = new EventLogger({
    source: 'PDF Download Server'
});
