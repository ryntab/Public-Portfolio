<img src="https://github.com/ryntab/Public-Portfolio/blob/main/Banner.jpg">

`~Current Version:1.0.0~`

# Public.com Stock Display and Portfolio Shortcodes 🤑
- Display your Public.com portfolio, watchlists, and posts in your Wordpress blog.
- Easily embed and automatically reference stocks using simple notation. 
- Supports Acato's WP REST Caching.

## User Portfolio Watchlist

Every 60 seconds, this plugin curls the Public.com API and saves your watchlist data to your sites WP options table. The fetch interval can be changed, but 60 seconds is the advised period of time to prevent abuse.

**Display your watchlist stocks**

```
[public_portfolio_watchlist]
```

**Exclude specific stocks from your watchlist**

```
[public_portfolio_watchlist hideticker="AMD,GME,LZB"]
```

**Show only specific stocks from your watchlist**

```
[public_portfolio_watchlist ticker="CLNE,SOFI,AMD,AMC,BHAT"]
```

#### **Note:** Special Cases
> If you exclude and include a watchlist stock, it will always default to being hidden. Hiding a stock is the override property. In the shortcode below $HUYA will not be shown.
```
[public_portfolio_watchlist hideticker="HUYA" ticker="HUYA"]
```



## Basic User Portfolio

Display the basic information from your Public.com portfolio.
```
[public_portfolio_bio]
```

<img src="https://github.com/ryntab/Public-Portfolio/blob/main/Profile.jpg">

## Automatic stock referencing

Embed stock references with super simple notation and automatically display self fetching stock tool tips with time chart graphs and other display options. In order to reference a stock in a post or page, just add a '$' before the stock symbol. The stock will be detected automatically.
```
While the bull case is pretty clear for why $AMD will reach $100, the stock has not had the same type of success that Nvidia has had lately.
```

<p align="center">
    <br/><br/>
    Made with ❤ by <a href="ryntab.com">Ryan Taber</a>.<br/>
    <a href="#">For Wordpress</a>
    This plugin is not maintained or affiliated with Public.com
</p>
