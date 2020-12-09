const penthouse = require("penthouse");
const fs = require("fs");

penthouse({
    url: 'https://local.arlgh.com/forms/start-page',
    //cssString: ' ',
    css: 'C:\\xampp\\htdocs\\fms\\public\\assets\\fontastic\\styles.css'
})
    .then(criticalCss => {
        // use the critical css
        fs.writeFileSync('C:\\xampp\\htdocs\\fms\\public\\assets\\fontastic\\critical.css', criticalCss);
    })
