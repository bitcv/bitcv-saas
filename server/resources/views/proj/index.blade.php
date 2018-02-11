<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <title>{{$proj['name_cn']}}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="/css/normalize.css">
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/solido.css">
        <link rel="stylesheet" href="/css/isotope.css">
        <link rel="stylesheet" href="/css/responsive.css">
        <link rel="stylesheet" href="/css/vegas/jquery.vegas.css">
        <link rel="stylesheet" href="/css/popup/magnific-popup.css">
        <link rel="stylesheet" href="/js/superslides-0.6.2/dist/stylesheets/superslides.css">
                
        <!-- Colors Style -->
        <link rel="stylesheet" href="/css/color/dark.css">
        <link rel="stylesheet" href="/css/color/black.css">
        <link rel="stylesheet" href="/css/color/green.css">
        <link rel="stylesheet" href="/css/color/red.css">
        <link rel="stylesheet" href="/css/color/yellow.css">
        <link rel="stylesheet" href="/css/color/purple.css">
        <link rel="stylesheet" href="/css/color/turquoise.css">
        <link rel="stylesheet" href="/css/color/orange.css">
        <link rel="stylesheet" href="/css/color/blue.css">
        
        <script src="/js/libs/modernizr-2.6.2.min.js"></script>
        
        
    </head>
    <body>
        <div id="mask">   
            <div class="loader">
              <img src="img/loading.gif" alt='loading'>
            </div>
        </div>
        <div class="color-picker">
            <div class="picker-btn"></div>
            <div class="pickerTitle">Style Switcher</div>
            <div class="pwrapper">
                <div class="pickersubTitle">Layout Selector</div>
                <div class="light-version"><img title="" alt='img' src="img/light.jpg"></div>
                <div class="dark-version"><img title="" alt='img' src="img/dark.jpg"></div>
                <div class="pickersubTitle"> Color scheme </div>
                <div class="picker-blue"></div>
                <div class="picker-black"></div>
                <div class="picker-green"></div>
                <div class="picker-yellow"></div>
                <div class="picker-red"></div>
                <div class="picker-purple"></div>
                <div class="picker-turquoise"></div>
                <div class="picker-orange"></div>
                <div class="clear nopick"></div>
            </div>
        </div>       
        
        <div id="anchor1"></div>
        
          <div id="slides-1">
            <div class="overlay"></div>
            <div class="slides-container">
              <img src="img/slider/01.jpg" alt="">
              <img src="img/slider/02.jpg" alt="">
              <img src="img/slider/03.jpg" alt="">
              <img src="img/slider/04.jpg" alt="">
            </div>
            <nav class="slides-navigation">
              <a href="#" class="next"></a>
              <a href="#" class="prev"></a>
            </nav>
          </div>        
        
        <!--<section id="home" class="clear">-->
            <!--<div id="anchor1">--><!--<div>
                <ul class="slider-controls">
                    <li><a id="vegas-next" class="s-next" href="#"></a></li>
                    <li><a id="vegas-prev" class="s-prev" href="#"></a></li>
                </ul>
            </div>
        </section>-->
        <div class="main-title">
            <div class="title-container">
                <div class="welcome">{{$proj['name_en']}}</div>
                <ul>
                    <li class="t-current">{{$proj['name_cn']}}</li>
                </ul>
                <div class="spacer"></div>
                <!--<a href="#anchor2"><div class="slider-logo">Get Started</div></a>-->
                <div class="second-title">{{$proj['short_desc']}}</div>
                <a href="{{$proj['white_paper_url']}}"><div class="buy-logo">下载白皮书<span></span></div></a>
            </div>
        </div>
        <div id="logx"></div>
        <header class="header">
            <div class="logo"><span><span></span></span>{{$proj['name_cn']}}</div>
            <nav id="nav2" role="navigation">
                <a class="jump-menu" title="Show navigation">Show navigation</a>
                <ul>
                    <li class="current"><a href="#anchor1">首页</a></li>
                    <li><a href="#anchor2">产品</a></li>
                    <li><a href="#anchor3">团队</a></li>
                    <li><a href="#anchor4">服务</a></li>
                    <li><a href="#anchor5">顾问</a></li>
                    <li><a href="#anchor6">联系</a></li>
                </ul>
            </nav>
            <nav class="menu">
                <ul id="nav">
                    <li class="current"><a href="#anchor1">首页</a></li>
                    <li><a href="#anchor2">产品</a></li>
                    <li><a href="#anchor3">团队</a></li>
                    <li><a href="#anchor4">服务</a></li>
                    <li><a href="#anchor5">顾问</a></li>
                    <!--<li><a href="#">blog</a></li>-->
                    <li><a href="#anchor6">联系</a></li>
                </ul>
            </nav>
        </header>
        
        <article id="anchor2" class="content menu-top dark">
            <header class="title one">产品实现</header>
            <div class="spacer"></div>
            <div class="title two">我们的产品取得了很好的反向，市场上得到广泛的关注。</div>
            <section class="featured-slider">
                <div id="ca-container" class="ca-container">
                    <div class="nav-featured">
                        <div class="prev-featured"></div>
                        <a href="#anchor5"><div class="btn-featured">查看顾问</div></a>
                        <div class="next-featured"></div>
                    </div>
                    <div class="main-carousel hideme dontHide">
                        <div class="ca-wrapper">
                            <div class="ca-item ca-item-1">
                                <div class="f-single">
                                    <a href="img/featured/big/big04.jpg">
                                        <div class="f-image">
                                            <img src="img/featured/feat-01.jpg" alt='img'>
                                            <div class="image-hover-overlay"></div>
                                            <span class="f-category"></span>
                                            <div class="portfolio-meta">
                                                <div>Single Portfolio - Wide Image</div>
                                                <div class="clear"></div>
                                                <div>Photography</div>
                                            </div>
                                        </div>
                                        <div class="f-info">Young Eckless</div>
                                    </a>
                                </div>
                            </div>
                            <div class="ca-item ca-item-2">
                                <div class="f-single">
                                    <a href="img/featured/big/big05.jpg">
                                        <div class="f-image">
                                            <img src="img/featured/feat-02.jpg" alt='img'>
                                            <div class="image-hover-overlay"></div>
                                            <span class="f-category"></span>
                                            <div class="portfolio-meta">
                                                <div>Single Portfolio - Wide Image</div>
                                                <div class="clear"></div>
                                                <div>Photography</div>
                                            </div>
                                        </div>
                                        <div class="f-info">Sweet Home</div>
                                    </a>
                                </div>
                            </div>
                            <div class="ca-item ca-item-3">
                                <div class="f-single">
                                    <a href="img/featured/big/big02.jpg">
                                        <div class="f-image">
                                            <img src="img/featured/feat-03.jpg" alt='img'>
                                            <div class="image-hover-overlay"></div>
                                            <span class="f-category"></span>
                                            <div class="portfolio-meta">
                                                <div>Single Portfolio - Wide Image</div>
                                                <div class="clear"></div>
                                                <div>Photography</div>
                                            </div>
                                        </div>
                                        <div class="f-info">Desk Showcase</div>
                                    </a>
                                </div>
                            </div>
                            <div class="ca-item ca-item-4">
                                <div class="f-single">
                                    <a href="http://www.youtube.com/watch?v=bpqhStV2_rc" class="mfp-iframe" title="My YouTube Video">
                                        <div class="f-image">
                                            <img src="img/featured/feat-04.jpg" alt='img'>
                                            <div class="image-hover-overlay"></div>
                                            <span class="f-category"></span>
                                            <div class="portfolio-meta">
                                                <div>Single Portfolio - Wide Image</div>
                                                <div class="clear"></div>
                                                <div>Video</div>
                                            </div>
                                        </div>
                                        <div class="f-info">Motion Reel</div>
                                    </a>
                                </div>
                            </div>
                            <div class="ca-item ca-item-5">
                                <div class="f-single">
                                    <a href="img/featured/big/big03.jpg">
                                        <div class="f-image">
                                            <img src="img/featured/feat-05.jpg" alt='img'>
                                            <div class="image-hover-overlay"></div>
                                            <span class="f-category"></span>
                                            <div class="portfolio-meta">
                                                <div>Single Portfolio - Wide Image</div>
                                                <div class="clear"></div>
                                                <div>Photography</div>
                                            </div>
                                        </div>
                                        <div class="f-info">Shutter Island</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </article>
        <article class="content light">
            <div class="full">
                <section class="half car-show-1">
                    <header class="title-one">Our Offices</header>
                    <div class="title-two">Amazing Studio</div>
                    <div class="show hideme dontHide">
                        <div class="caroussel">
                            <div class="caroussel-list">
                                <div class="car-img"><img src="img/caroussel/caroussel-01.jpg" alt='img'></div>
                                <div class="car-img"><img src="img/caroussel/caroussel-02.jpg" alt='img'></div>
                                <div class="car-img"><img src="img/caroussel/caroussel-03.jpg" alt='img'></div>
                            </div>
                        </div>
                        <div class="car-prev"></div>
                        <div class="car-next"></div>
                    </div>
                    <div class="controller">
                        <ul>
                        </ul>
                    </div>
                </section>
                <section class="half">
                    <header class="title-one">Creative Agency</header>
                    <div class="title-two">About Us</div>
                    <div class="half-content hideme dontHide">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et <a href="#">dolore magna</a> aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.<br><br>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur <a href="#">sint occaecat</a> cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. pariatur. Stet clita kasd gubergren, no sea takimata est. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. pariatur sunt in culpa qui. Stet clita kasd gubergren, no sea <a href="#">takimata</a> est.
                    </div>
                </section>
            </div>
            <div class="clear"></div>
        </article>
        <div class="clear"></div>
        <article class="parallax p-one">
            <div class="p-title-one">Easy Customizable</div>
            <div class="p-title-two">Add Your Content</div>
            <div class="spacer"></div>
            <div class="p-image-02">
                <!--div class="p-image-second hideme-slide dontHide delay"><img src="{{$proj['banner_url']}}" alt='img'></div-->
                <div class="p-image-first hideme-slide dontHide"><img src="{{$proj['banner_url']}}" alt='img'></div>
            </div>
        </article>

        <article id=anchor3 class="content dark">
            <header class="title one">团队</header>
            <div class="spacer"></div>
            <section class="team-box">
                <div class="s-container team-grid">
                    <div class="t-list">

                        @foreach ($proj['memberList'] as $m)
                        <div class="t-element hideme dontHide">
                            <div class="t-photo">
                                <div class="image-hover-overlay"></div>
                                <div class="portfolio-meta">
                                    <div>{{$m['intro']}}</div>
                                </div>
                                <img src="{{$m['photo_url']}}" alt='img' style="width:100%">
                            </div>
                            <div class="t-data">
                                <div class="t-name">{{$m['name']}}</div>
                                <div class="t-info">{{$m['position']}}</div>
                                <div class="t-spacer"></div>
                                
                            </div>
                        </div>
                        @endforeach
                        
                    </div>
                </div>
            </section>
            <div class="clearfix"></div>
        </article>
        <article class="content light">
            <section class="full">
                <div class="title-full-one">enjoy them</div>
                <div class="title-full-two">Some Fun Facts</div>
                <div class="f-container">
                    <div class="f-element hideme dontHide">
                        <div class="f-ico s-one"></div>
                        <div class="milestone-counter" data-perc="99">
                            <span class="milestone-count highlight">0</span> <!-- Initial Value = 0 -->
                            <div class="milestone-details">Tweets</div>
                        </div>
                    </div>
                    <div class="f-element hideme dontHide">
                        <div class="f-ico s-two"></div>
                        <div class="milestone-counter" data-perc="85">
                            <span class="milestone-count highlight">0</span> <!-- Initial Value = 0 -->
                            <div class="milestone-details">Clients Worked</div>
                        </div>
                    </div>
                    <div class="f-element hideme dontHide">
                        <div class="f-ico s-three"></div>
                        <div class="milestone-counter" data-perc="120">
                            <span class="milestone-count highlight">0</span> <!-- Initial Value = 0 -->
                            <div class="milestone-details">Projects Completed</div>
                        </div>
                    </div>
                    <div class="f-element hideme dontHide">
                        <div class="f-ico s-four"></div>
                        <div class="milestone-counter" data-perc="250">
                            <span class="milestone-count highlight">0</span> <!-- Initial Value = 0 -->
                            <div class="milestone-details">Cofee Cups</div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="clear"></div>
        </article>
        <article class="parallax p-two">
            <div class="p-title-one p-dark">Ultra Responsive</div>
            <div class="p-title-two">On both Mobile & Desktop</div>
            <div class="spacer newtr"></div>
            <div class="p-image-01 hideme-slide2 dontHide"><img src="img/parallax/p-image-01.png" alt='img'></div>
        </article>
        <article id=anchor4 class="content dark">
            <header class="title one">Our Services</header>
            <div class="spacer"></div>
            <div class="title two">We take pride in our interdisciplinary approach in crafting beautiful, functional and engaging work that delights and delivers results.</div>
            <div class="s-container services-container">
                <section class="services">
                       <!--<div class="s-element">
                            <div class="s-ico"></div>
                            <div class="s-info"><span>Developer Track</span><br><br>Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Stet clita kasd gubergren.</div>
                        </div>
                        <div class="s-element">
                            <div class="s-ico"></div>
                            <div class="s-info"><span>SEO Analytics</span><br><br>Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Stet clita kasd gubergren.</div>
                        </div>
                        <div class="s-element">
                            <div class="s-ico"></div>
                            <div class="s-info"><span>Amazing Ideas</span><br><br>Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Stet clita kasd gubergren.</div>
                        </div>-->
                        <div class="sl-element hideme dontHide">
                            <div class="sl-ico sl-config"></div>
                            <div class="sl-title">UX/UI</div>
                            <div class="tooltip s-roll">
                                <div class="details">
                                    <ul class="feature-list">
                                        <li><span class="list-dot"></span>Facebook Apps and Pages</li>
                                        <li><span class="list-dot"></span>Twitter Setup</li>
                                        <li><span class="list-dot"></span>Social Strategy and Planning</li>
                                    </ul>
                                </div>
                            </div>
                            <span class="arrow-down s-roll"></span>
                        </div>
                        <div class="sl-element hideme dontHide">
                            <div class="sl-ico sl-diamond"></div>
                            <div class="sl-title">design</div>
                            <div class="tooltip s-roll">
                                <div class="details">
                                    <ul class="feature-list">
                                        <li><span class="list-dot"></span>Facebook Apps and Pages</li>
                                        <li><span class="list-dot"></span>Twitter Setup</li>
                                        <li><span class="list-dot"></span>Social Strategy and Planning</li>
                                    </ul>
                                </div>
                            </div>
                            <span class="arrow-down s-roll"></span>
                        </div>
                        <div class="sl-element hideme dontHide">
                            <div class="sl-ico sl-globe"></div>
                            <div class="sl-title">Identity</div>
                            <div class="tooltip s-roll">
                                <div class="details">
                                    <ul class="feature-list">
                                        <li><span class="list-dot"></span>Facebook Apps and Pages</li>
                                        <li><span class="list-dot"></span>Twitter Setup</li>
                                        <li><span class="list-dot"></span>Social Strategy and Planning</li>
                                    </ul>
                                </div>
                            </div>
                            <span class="arrow-down s-roll"></span>
                        </div>
                        <div class="sl-element hideme dontHide">
                            <div class="sl-ico sl-pointer"></div>
                            <div class="sl-title">social</div>
                            <div class="tooltip s-roll">
                                <div class="details">
                                    <ul class="feature-list">
                                        <li><span class="list-dot"></span>Facebook Apps and Pages</li>
                                        <li><span class="list-dot"></span>Twitter Setup</li>
                                        <li><span class="list-dot"></span>Social Strategy and Planning</li>
                                    </ul>
                                </div>
                            </div>
                            <span class="arrow-down s-roll"></span>
                        </div>
                        <div class="sl-element hideme dontHide">
                            <div class="sl-ico sl-clock"></div>
                            <div class="sl-title">Motion</div>
                            <div class="tooltip s-roll">
                                <div class="details">
                                    <ul class="feature-list">
                                        <li><span class="list-dot"></span>Facebook Apps and Pages</li>
                                        <li><span class="list-dot"></span>Twitter Setup</li>
                                        <li><span class="list-dot"></span>Social Strategy and Planning</li>
                                    </ul>
                                </div>
                            </div>
                            <span class="arrow-down s-roll"></span>
                        </div>
                        <div class="clear"></div>
               </section>
           </div>
        </article>
        <article class="content light">
            <div class="full">
                <section class="half hideme dontHide">
                    <div class="title-one">WHAT WE DO</div>
                    <div class="title-two">Our Awesome Skills</div>
                    <div class="half-content">
                        <div class="sk-container">
                            <div class="skill-content">
                                <div class="progress-bar skill-1">
                                  <div class="skill-in" title="90"><div class="info-skills">web developement <span>- 90%</span></div></div>
                                </div>
                            </div>
                            <div class="skill-content">
                                <div class="progress-bar skill-2">
                                  <div class="skill-in" title="70"><div class="info-skills">web design <span>- 70%</span></div></div>
                                </div>
                            </div>
                            <div class="skill-content">
                                <div class="progress-bar skill-3">
                                  <div class="skill-in" title="80"><div class="info-skills">video edition <span>- 80%</span></div></div>
                                </div>
                            </div>
                            <div class="skill-content">
                                <div class="progress-bar skill-4">
                                  <div class="skill-in" title="60"><div class="info-skills">marketing online <span>- 60%</span></div></div>
                                </div>
                            </div>
                            <div class="skill-content">
                                <div class="progress-bar skill-5">
                                  <div class="skill-in" title="100"><div class="info-skills">communication <span>- 100%</span></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="half car-show-2 hideme dontHide">
                    <div class="title-one">Testimonials</div>
                    <div class="title-two">Our Happy Clients</div>
                    <div class="show">
                        <div class="caroussel-2">
                            <div class="caroussel-list-2">
                                <div class="car-quote">
                                    <div class="testimonials">
                                        <div class="avatar"><img src="img/testimonials/avatar-01.jpg" alt='img'></div>
                                        <div class="comment">"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."<br><br><span>Julia Smith, </span>CEO</div>
                                        <div class="clear q-spacer"></div>
                                        <div class="avatar"><img src="img/testimonials/avatar-02.jpg" alt='img'></div>
                                        <div class="comment">"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."<br><br><span>Steve Ronald, </span>Developer</div>
                                    </div>
                                </div>
                                <div class="car-quote">
                                    <div class="testimonials">
                                        <div class="avatar"><img src="img/testimonials/avatar-01.jpg" alt='img'></div>
                                        <div class="comment">"Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Stet clita kasd gubergren, no sea takimat, lorem ipsum st."<br><br><span>Julia Smith, </span>CEO</div>

                                        <div class="clear q-spacer"></div>
                                        <div class="avatar"><img src="img/testimonials/avatar-02.jpg" alt='img'></div>
                                        <div class="comment">"Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Stet clita kasd gubergren, no sea takimat, lorem ipsum st."<br><br><span>Steve Ronald, </span>Developer</div>
                                    </div>
                                </div>
                                <div class="car-quote">
                                    <div class="testimonials">
                                        <div class="avatar"><img src="img/testimonials/avatar-01.jpg" alt='img'></div>
                                        <div class="comment">"Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Stet clita kasd gubergren, no sea takimat, lorem ipsum st."<br><br><span>Julia Smith, </span>CEO</div>

                                        <div class="clear q-spacer"></div>
                                        <div class="avatar"><img src="img/testimonials/avatar-02.jpg" alt='img'></div>
                                        <div class="comment">"Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Stet clita kasd gubergren, no sea takimat, lorem ipsum st."<br><br><span>Steve Ronald, </span>Developer</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="car-prev-2"></div>
                        <div class="car-next-2"></div>
                    </div>
                    <div class="controller-2">
                        <ul>
                        </ul>
                    </div>
                </section>
            </div>
            <div class="clear"></div>
        </article>
        <div class="clear"></div>
        
        <article id=anchor5 class="content dark">
            <header class="title one">Our Portfolio</header>
            <div class="spacer"></div>
            <div class="title two">We have been privileged to work and grow with a diverse range of clients. We have worked with brands in Canada, USA, UK, and many others.</div>
            <div id="portfolio" class="container">
                <div class="section portfoliocontent">
                    <section id="options" class="clear">
                        <div id="filters" class="option-set clearfix foliomenu hideme dontHide" data-option-key="filter">
                          <a href="#filter" data-option-value="*" class="p-selected folio-btn"><div class="portfolio-btn">All</div></a>
                          <a href="#filter" data-option-value=".graphic" class="folio-btn"><div class="portfolio-btn">Graphic Design</div></a>
                          <a href="#filter" data-option-value=".vector" class="folio-btn"><div class="portfolio-btn">Photography</div></a>
                          <a href="#filter" data-option-value=".music" class="folio-btn"><div class="portfolio-btn">Website</div></a>
                          <a href="#filter" data-option-value=".illustration" class="folio-btn"><div class="portfolio-btn">Motion</div></a>
                        </div>
                    </section>
                    <div class="clear"></div>
                    <div id="project-show"></div>
                    <section class="project-window">
                        <div class="project-content"></div><!-- AJAX Dinamic Content -->
                    </section>
                    <section id="i-portfolio" class="clear">
                        <div class="inici"></div>
                        
                        <div class="ch-grid element graphic music" id="portfolio-1.html">
                            <img class="ch-item" src="img/portfolio/thumb-01.jpg" alt='img'>
                            <div>
                                <span>
                                    <span class="p-category"></span>
                                    Kallo
                                    <span class="cat2">Graphic Design</span>
                                </span>
                            </div>
                        </div>

                        <div class="ch-grid element graphic vector" id="portfolio-2.html">
                            <img class="ch-item" src="img/portfolio/thumb-02.jpg" alt='img'>
                            <div>
                                <span><span class="p-category"></span>The Inside<span class="cat2">Photography</span></span>
                            </div>
                        </div>
                        
                        <div class="ch-grid element illustration" id="portfolio-1b.html">
                            <img class="ch-item" src="img/portfolio/thumb-03.jpg" alt='img'>
                            <div>
                                <span><span class="p-category"></span>Leitberg<span class="cat2">Graphic Design</span></span>
                            </div>
                        </div>
                        
                        <div class="ch-grid element illustration" id="portfolio-3.html">
                            <img class="ch-item" src="img/portfolio/thumb-04.jpg" alt='img'>
                            <div>
                                <span><span class="p-category"></span>Fashion Brand<span class="cat2">Graphic Design</span></span>
                            </div>
                        </div>
                        
                        <div class="ch-grid element graphic vector" id="portfolio-1c.html">
                            <img class="ch-item" src="img/portfolio/thumb-05.jpg" alt='img'>
                            <div>
                                <span><span class="p-category"></span>The Chop Shop<span class="cat2">Website</span></span>
                            </div>
                        </div>

                        <div class="ch-grid element graphic vector illustration" id="portfolio-1d.html">
                            <img class="ch-item" src="img/portfolio/thumb-06.jpg" alt='img'>
                            <div>
                                <span><span class="p-category"></span>Prego<span class="cat2">Website</span></span>
                            </div>
                        </div>
                        
                        <div class="ch-grid element music" id="portfolio-4.html">
                            <img class="ch-item" src="img/portfolio/thumb-07.jpg" alt='img'>
                            <div>
                                <span><span class="p-category"></span>Behurs<span class="cat2">Graphic Design</span></span>
                            </div>
                        </div>
                        
                        <div class="ch-grid element graphic vector music" id="portfolio-1e.html">
                            <img class="ch-item" src="img/portfolio/thumb-08.jpg" alt='img'>
                            <div>
                                <span><span class="p-category"></span>The New Girl<span class="cat2">Photography</span></span>
                            </div>
                        </div>
                        
                        <div class="ch-grid element illustration music" id="portfolio-4b.html">
                            <img class="ch-item" src="img/portfolio/thumb-09.jpg" alt='img'>
                            <div>
                                <span><span class="p-category"></span>Shut Up & Shoot<span class="cat2">Graphic Design</span></span>
                            </div>
                        </div>
                        
                        <div class="ch-grid element music" id="portfolio-3b.html">
                            <img class="ch-item" src="img/portfolio/thumb-10.jpg" alt='img'>
                            <div>
                                <span><span class="p-category"></span>Kulisha 79000<span class="cat2">Graphic Design</span></span>
                            </div>
                        </div>
                        
                        <div class="final"></div>
                    </section>
                </div>
            </div>
            <div class="clear"></div>
            <section class="list_carousel responsive hideme dontHide">
                <ul id="logos">
                    <li><img src="img/logos/logo-01.png" alt="logo"></li>
                    <li><img src="img/logos/logo-02.png" alt="logo"></li>
                    <li><img src="img/logos/logo-03.png" alt="logo"></li>
                    <li><img src="img/logos/logo-04.png" alt="logo"></li>
                    <li><img src="img/logos/logo-05.png" alt="logo"></li>
                    <li><img src="img/logos/logo-06.png" alt="logo"></li>
                    <li><img src="img/logos/logo-07.png" alt="logo"></li>
                </ul>
                <div class="clearfix"></div>
            </section>
            <section class="img-spacer">
                <div class="img-item hideme dontHide"><img src="img/imac.png" alt='img'></div>
            </section>
        </article>
        <footer id=anchor6 class="footer light">
            <div class="footer-container">
                <div class="title one no-top">Contact</div>
                <div class="spacer"></div>
                <div class="title two f-bottom">We like to create things with fun, like-minded people.<br> Feel free to say hello!</div>
                <div class="foot-third hideme dontHide">
                    <div class="f-title-one">contact</div>
                    <div class="f-title-two">Visit Our Office</div>
                    <div class="f-data adress"><img src="img/adress-ico.png" alt='img'> Adress: <span>1234 Street Name, City Name</span></div>
                    <div class="f-data phone"><img src="img/phone-ico.png" alt='img'> Phone: <span>(123) 456-7890</span></div>
                    <div class="f-data e-mail"><img src="img/mail-ico.png" alt='img'> Email: <span>theme@solido.com</span></div>
                </div>
                <div class="foot-third hideme dontHide">
                    <div class="f-title-one">contact</div>
                    <div class="f-title-two">Business Hours</div>
                    <div class="f-data hour-1"><img src="img/hours-ico.png" alt='img'> Mon. - Fri. <span>8am to 5pm</span></div>
                    <div class="f-data hour-2"><img src="img/hours-ico.png" alt='img'> Sat. <span>8am to 11am</span></div>
                    <div class="f-data hour-3"><img src="img/hours-ico.png" alt='img'> Sun. <span>Closed</span></div>
                </div>
                <div class="foot-third hideme dontHide">
                    <form method="post" id="contact" class="peThemeContactForm" action="mail.php">
                        <div id="personal" class="bay form-horizontal">
                            <div class="control-group"><!--name field-->
                                <div class="controls">
                                    <input class="required span9" type="text" name="author" data-fieldid="0" value="Full Name" onClick="if(this.value=='Full Name') this.value=''" onBlur="if(this.value=='') this.value='Full Name'">
                                </div>
                            </div>
                            <div class="control-group"><!--email field-->
                                <div class="controls">
                                    <input class="required span9" type="email" name="email" data-fieldid="1" value="Your Email" onClick="if(this.value=='Your Email') this.value=''" onBlur="if(this.value=='') this.value='Your Email'">
                                </div>
                            </div>
                            <div class="control-group"><!--message field-->
                                <div class="controls">
                                    <textarea name="message" rows="12" class="required span9" data-fieldid="2" onClick="if(this.value=='Type Message') this.value=''" onBlur="if(this.value=='') this.value='Type Message'">Type Message</textarea>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls send-btn">
                                    <button type="submit" class="contour-btn red">Send Message</button>
                                </div>
                            </div>
                        </div>
                        <div class="notifications">
                            <div id="contactFormSent" class="formSent alert alert-success">
                                <strong>Your Message Has Been Sent!</strong> Thank you for contacting us.</div> 
                            <div id="contactFormError" class="formError alert alert-error">
                                <strong>Oops, An error has ocurred!</strong> See the marked fields above to fix the errors.</div>
                        </div>
                    </form>
                </div>
            </div>
        </footer>
        <a href="#" class="scrollup">^</a>
        <div class="socialFooter">
            <div class="social-icons">
                <div class="social">
                    <a href="https://es-es.facebook.com/" target="_blank"><div class="face"></div></a>
                    <a href="https://twitter.com/" target="_blank"><div class="twitt"></div></a>
                    <a href="https://plus.google.com/" target="_blank"><div class="plus"></div></a>
                </div>
            </div>
            <div class="clear"></div>
            <div class="copy">2018 © BitCV. All rights reserved.</div>            
        </div>
        <script src="/js/libs/jquery-1.9.1.min.js"></script>
        <script src="/js/libs/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/js/libs/jquery.carouFredSel-6.2.1-packed.js"></script>
        <script src="/js/libs/jquery.smoothwheel.js"></script>
        <script src="/js/main.js"></script>
        <script src="/js/libs/jquery.inview.js"></script>
        <script type="text/javascript"  src="/js/libs/jquery.sticky.js"></script>
        <script type="text/javascript" src="/js/caroussel/jquery.easing.1.3.js"></script>
        <script type="text/javascript"  src="/js/portfolio.js"></script>
        <!--<script type="text/javascript" src="js/vegas/jquery.vegas.js"></script>-->
        <script src="/js/superslides-0.6.2/dist/jquery.superslides.js" type="text/javascript" charset="utf-8"></script>

        <script type="text/javascript" src="/js/libs/jquery.hoverdir.js"></script>
        <script src="/js/libs/jquery.nav.js"></script>
        <script src="/js/libs/jquery.magnific-popup.js"></script> 
        <script type="text/javascript" src="/js/caroussel/jquery.contentcarousel.js"></script>
        <script src="/js/libs/jquery.isotope.min.js"></script>
        <script src="js/plugins.js"></script>
        <!--<script type="text/javascript" src="js/vegas/vegas_slider.js"></script>-->
        <script src="/js/libs/jquery.validate.js"></script>
        <script src="/js/libs/jquery.form.js"></script>        
        <script src="js/test.js"></script>
    </body>
</html>

