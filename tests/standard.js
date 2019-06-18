const puppeteer = require('puppeteer');
(async () => {
  const browser = await puppeteer.launch({headless:false, slowMo: 100});
  const page = await browser.newPage();
  //Go to my page and wait until the page loads
  await page.goto('https://www.baui.com.ar/cor', {waitUntil: 'networkidle2'});
  await page.waitForSelector('body > h1');

  //Click Selectmenu
   await page.click('#metodo-button > span.ui-selectmenu-text');
   await page.click('#ui-id-3');
  //Click on the submit button
  await page.click('#resolver');  
  await page.screenshot({ path: 'tests/standard.png', fullPage: true });
  await browser.close();
})();