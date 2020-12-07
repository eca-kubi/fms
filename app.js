const puppeteer = require('puppeteer');
const Koa = require('koa');
const KoaRouter = require("koa-router");

const app = new Koa();
const router = new KoaRouter();
const defualtUrl = 'https://local.arlgh.com/forms/visitor-access-form/print/';

(async () => {
    browser = await puppeteer.launch({
        headless: true,
        args: ['--remote-debugging-port=9222']
    });
})();

let takeScreenshot = async (url) => {
    try {
        const wsbrowser = await puppeteer.connect({
            browserURL: "http://localhost:9222/json/version",
            defaultViewport: null
        });
        const page = await wsbrowser.newPage();
        await page.goto(url ? url : defualtUrl, { waitUntil: 'networkidle2' });
        return await page.screenshot({ path: 'C:/xampp/htdocs/fms/public/images/screenshot.png' })
            .catch((reason) => console.log(reason))
            .finally(() => page.close());
    } catch (error) {
        console.log(error)
    }
}

let createPDF = async (url) => {
    try {
        const wsbrowser = await puppeteer.connect({
            browserURL: "http://localhost:9222/json/version"
        });

        const page = await wsbrowser.newPage();
        await page.goto(url, { waitUntil: 'networkidle2' });
        let buffer = await page.pdf({
            path: 'C:/xampp/htdocs/fms/public/pdf/temp.pdf',
            format: 'A4',
            width: '8.5in',
            height: '11in'
        }).catch((reason) => console.log(reason));

        await page.close()

        return buffer
    } catch (error) {
        console.log(error)
    }
}

router.get('/screenshot', async (ctx) => {
    let url = ctx.params.url ? ctx.params.url : defualtUrl
    await takeScreenshot(url).then((buffer) => {
        ctx.response.set('Content-Type', 'image/png')
        ctx.body = buffer
        ctx.attachment('screenshot.png')
    });
});

router.get('/download/:formId', async (ctx) => {
    let url = ctx.params.formId ? (defualtUrl + ctx.params.formId) : defualtUrl
    await createPDF(url).then((buffer) => {
        ctx.response.set('Content-Type', 'application/pdf')
        ctx.body = buffer;
        ctx.attachment('Visitor-Access-Form.pdf', {})
    });
})

router.get('/', async  (ctx) => {
    ctx.body = 'Server is running successfully.'
});

app.use(router.routes()).use(router.allowedMethods());

app.listen(3000, () => {
    console.log('Server started...');
})
