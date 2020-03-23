
body {
  width: auto;
  margin: 0;
  padding: 0;
}

header {
  position: relative;
  width:100%; 
  background: white; 
  height: 60px; 
  line-height: 60px;
  border-bottom: 1px solid #ddd;
}

#sidebar {
  width: 250px;
  padding: 10px;
  background: #407cad;
}

#sidebar ul {
  list-style: none;
  margin: 10px 0;
  padding: 0;
}

#sidebar li {
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

#sidebar a {
  display: block;
  color: #fff;
  text-decoration: none;
  padding: 10px 6px;
  font-family: "Source Sans Pro", Arial, sans-serif;
}

#sidebar amp-accordion ul {
  margin: 4px 0 4px 15px;
  background: #5A96C7;
}

#sidebar amp-accordion ul li:last-child {
  border-bottom: none;
}

.branding {
  padding: 7px 10px;
}

article {
  max-width: 800px;
  margin: 0 auto;
  padding: 50px 0 10px;
  font-family: "Cabin", Arial, sans-serif;
}

article a {
  text-decoration: underline;
    color: #407cad; 
}
article a:hover {
  color: #478220;
}

h1,
h2 {
  margin: 0;
  padding: 4px 10px;
}

h1 {
    text-align: center;
    color: #5a5a5a;
    position: relative;
    margin: 0 0 10px 0;
    text-transform: uppercase;
    font-family: "Cabin", Arial, sans-serif;
    font-weight: 700;
    font-size: 24px;
}

h1.nav-accordion {
  text-align: left;
  color: #fff;
  background: transparent;
  padding: 10px 6px;
  font-family: "Source Sans Pro", Arial, sans-serif;
  text-transform: none;
  font-size: 1rem;
  font-weight: normal;
  border: none;
}

h2 {
  color: #407cad;
  font-family: "Lora", serif;
  font-weight: 700;
  font-size: 20px;
  margin: 0.5em;  
}

p {
  padding: 0.5em;
  margin: 0.5em;
  font-family: "Cabin", Arial, sans-serif;
  color: #4c4c4c;
  font-size: 16px;
  line-height: 1.5;
}

.hamburger,
.cross {
  background:none;
  position:absolute;
  top:0;
  right:60px;
  font-weight:bold;
  border:0;
  cursor:pointer;
  outline:none;
  z-index:1000;
}

.hamburger {
  line-height:45px;
  padding:5px 15px 0px 15px;
  color:#003f72;
  font-size:1.4em;
}

.cross {
  padding:7px 15px 0px 15px;
  font-size:3em;
  line-height:65px;
  color: rgba(255, 255, 255, 0.6);
  right: 0;
}

.header-phone {
  background: #fff url('/application/themes/theme_palmetto/images/buttons/phone-call-button.svg') no-repeat 2px 3px;
  text-indent: -9999em;
  width: 44px;
  height: 46px;
  display: inline-block;
  overflow: hidden;
  padding: 0; 
  position: absolute;
  top: 10px;
  right: 10px;
}

.orange-btn {
    color: white;
    border: 1px solid #df9369;
    display: block;
    text-align: center;
    text-transform: uppercase;
    text-decoration: none;
    -moz-box-shadow: 0 0 0 3px #d16428, 0 5px 5px #dbdbdb;
    -webkit-box-shadow: 0 0 0 3px #d16428, 0 5px 5px #dbdbdb;
    box-shadow: 0 0 0 3px #d16428, 0 5px 5px #dbdbdb;
    padding: 8px 0;
    background: #d57034;
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top, #d57034 0%, #d57034 50%, #d16428 51%, #c95821 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #d57034), color-stop(50%, #d57034), color-stop(51%, #d16428), color-stop(100%, #c95821));
    background: -webkit-linear-gradient(top, #d57034 0%, #d57034 50%, #d16428 51%, #c95821 100%);
    background: -o-linear-gradient(top, #d57034 0%, #d57034 50%, #d16428 51%, #c95821 100%);
    background: -ms-linear-gradient(top, #d57034 0%, #d57034 50%, #d16428 51%, #c95821 100%);
    background: linear-gradient(to bottom, #d57034 0%, #d57034 50%, #d16428 51%, #c95821 100%);
    font-family: "Cabin", Arial, sans-serif;
    font-weight: bold;
    font-size: 15px;
}

.blue-btn {
    color: #fff;
    border: 1px solid #4d799d;
    display: block;
    text-align: center;
    text-decoration: none;
    padding: 8px 0;
    -moz-box-shadow: 0 0 0 3px #003f72, 0px 5px 5px #dbdbdb;
    -webkit-box-shadow: 0 0 0 3px #003f72, 0px 5px 5px #dbdbdb;
    box-shadow: 0 0 0 3px #003f72, 0px 5px 5px #dbdbdb;
    background: #144e7d;
    background: -moz-linear-gradient(top, #144e7d 0%, #144e7d 50%, #003f72 51%, #003f72 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #144e7d), color-stop(50%, #144e7d), color-stop(51%, #003f72), color-stop(100%, #003f72));
    background: -webkit-linear-gradient(top, #144e7d 0%, #144e7d 50%, #003f72 51%, #003f72 100%);
    background: -o-linear-gradient(top, #144e7d 0%, #144e7d 50%, #003f72 51%, #003f72 100%);
    background: -ms-linear-gradient(top, #144e7d 0%, #144e7d 50%, #003f72 51%, #003f72 100%);
    background: linear-gradient(to bottom, #144e7d 0%, #144e7d 50%, #003f72 51%, #003f72 100%);
    font-family: "Cabin",Arial,sans-serif;
    font-weight: bold;
    font-size: 15px;
}

/* footer */
footer {
  text-align: center;
  margin: -8px 0 10px;
  padding: 40px 0;
  background: #3e78a8 url('/application/themes/theme_palmetto/images/layout/bg-footer.jpg') top center repeat-x;
  min-height: 250px;
  text-align: center;
  color:  #fff; 
}

footer p {
  margin: 0 0 11px;
  color: #fff;
  line-height: 18px;
  font-family: "Cabin", Arial, sans-serif;
  font-weight: 300;
  font-size: 14px;  
}

footer p a {
  color: #b0ddff;
}

footer .phone {
  font-size: 18px;
}

#footer-links {
  font-family: "Cabin", Arial, sans-serif;
  font-weight: 400;
  font-size: 14px;
  padding: 8px 0 10px 0;
  line-height: 22px;  
}

.copyright {
  position: relative;
  top: -20px;
  display: inline-block;
  margin: 0 10px 0 0;
}

#utility-nav ul {
  list-style: none;
  display: inline-block;
  margin: 0;
  padding: 0;
}

#utility-nav li {
  line-height: 33px;
  display: inline-block;
  margin: 0 .5rem;
  font-family: "Cabin", Arial, sans-serif;
  font-weight: 400;
  font-size: 14px;  
}

#utility-nav li a {
  color:  #b0ddff;
}

.footerbuttons {
  background: #fff;
  padding-bottom: 0;
  padding-top: 0;
  text-align: center;
  position: fixed;
  bottom: 0;
  width: 100%;
  z-index: 101;  
}


.booknowbutton {
  border: none;
  cursor: pointer;
  font-size: 12px;
  /*width: calc(50% - 26px);*/
  width: calc(50% - 2px);
  line-height: 46px;
  font-family: verdana;
  height: 46px;
  text-transform: uppercase;
  text-align: center;
  display: inline-block;
  vertical-align: top; 
  color: #fff;
  text-align: center;
  text-transform: uppercase;
  text-decoration: none;
  background: #d57034;
  font-family: "Cabin", Arial, sans-serif;
  font-size: 15px;

}

.footer-phone {
  background: #fff url('/application/themes/theme_palmetto/images/buttons/phone-call-button.svg') no-repeat 2px 3px;
  text-indent: -9999em;
  width: 44px;
  height: 46px;
  display: inline-block;
  overflow: hidden;
  padding: 0; 
  vertical-align: top; 
}

