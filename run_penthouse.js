const penthouse = require("penthouse");
const fs = require("fs");
const puppeteer = require('puppeteer') // installed by penthouse

/*const browserPromise = puppeteer.launch({
    //headless: false,
    ignoreHTTPSErrors: true,
    args: ['--disable-setuid-sandbox', '--no-sandbox'],
    // not required to specify here, but saves Penthouse some work if you will
    // re-use the same viewport for most penthouse calls.
    defaultViewport: {
        width: 1366,
        height: 768
    }
})*/
penthouse({
    url: 'https://local.arlgh.com/forms/',
    //cssString: ' ',
    forceInclude: [/col-(sm|md|xs|xl)-[0-9]/, '.alert', '.text-center', '.text-danger', '.m-auto', '.close'],
    css: 'C:\\xampp\\htdocs\\fms\\public\\assets\\css\\bootstrap.min.css',
    //timeout: 120000,
    /*puppeteer: {
       getBrowser: () => browserPromise
    },*/
    //unstableKeepBrowserAlive: true
})
    .then(criticalCss => {
        // use the critical css
        fs.writeFileSync('C:\\xampp\\htdocs\\fms\\critical2.css', criticalCss);
    })
