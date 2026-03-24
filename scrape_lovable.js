const puppeteer = require('puppeteer');
const fs = require('fs');

(async () => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();
  await page.goto('https://quick-theme-clone.lovable.app/', { waitUntil: 'networkidle2' });
  
  // Extract CSS
  const cssLinks = await page.$$eval('link[rel="stylesheet"]', links => links.map(l => l.href));
  let cssText = '';
  for (const link of cssLinks) {
    const res = await fetch(link);
    cssText += await res.text() + '\n';
  }
  
  // Extract HTML
  const html = await page.evaluate(() => document.documentElement.outerHTML);
  
  fs.writeFileSync('lovable_scraped.html', html);
  fs.writeFileSync('lovable_scraped.css', cssText);
  console.log('Scraped successfully');
  
  await browser.close();
})();
