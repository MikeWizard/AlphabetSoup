const puppeteer = require('puppeteer');
(async () => {
  const browser = await puppeteer.launch({headless:false, slowMo: 100});
  const page = await browser.newPage();
  //Go to my page and wait until the page loads
  await page.goto('https://www.baui.com.ar/cor', {waitUntil: 'networkidle2'});
  await page.waitForSelector('body > h1');

  // await page.focus('#width');
  // await page.keyboard.type('1');
  const input = await page.$('#width');
  await input.click({ clickCount: 3 })
  await input.type("1");
  //Click on the submit button
  await page.click('#verificar');  
  await page.screenshot({ path: 'tests/5x5matrix.png', fullPage: true });
  await browser.close();
})();