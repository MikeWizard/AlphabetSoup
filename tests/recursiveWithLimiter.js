const puppeteer = require('puppeteer');
(async () => {
  const browser = await puppeteer.launch({headless:false, slowMo: 100});
  const page = await browser.newPage();
  //Go to my page and wait until the page loads
  await page.goto('https://www.baui.com.ar/cor', {waitUntil: 'networkidle2'});
  await page.waitForSelector('body > h1');


  //Click on the submit button
  await page.click('#resolver');  
  await page.screenshot({ path: 'tests/recursiveWithLimiter.png', fullPage: true });
  await browser.close();
})();