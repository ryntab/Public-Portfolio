<img src="https://github.com/ryntab/Public-Portfolio/blob/main/Banner.jpg">

`~Current Version:1.0.0~`

# Public.com Stock Display and Portfolio Shortcodes 🤑
- Display your Public.com portfolio, watchlists, and posts in your Wordpress blog.
- Easily embed and automatically reference stocks using simple notation. 
- Supports Acato's WP REST Caching.

## User Portfolio Watchlist

Display your watchlist stocks.

```
[public_portfolio_watchlist]
```

Exclude specific stocks from your watchlist.

```
[public_portfolio_watchlist hideticker="AMD,GME,LZB"]
```

Show only specific stocks from your watchlist.

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

<p align="center">
    <br/><br/>
    Made with ❤ by <a href="ryntab.com">Ryan Taber</a>.<br/>
    <a href="#">For Wordpress</a>
    This plugin is not maintained or affiliated with Public.com
</p>
