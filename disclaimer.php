<?php
include_once("config.php");
?>
<!DOCTYPE html>
<html dir="ltr" lang="de">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$WebsiteTitle ?></title>
	<style>
	<!--
	body{
		font-family: "Open Sans", Arial, Helvetica, sans-serif;
		color: #7A7A7A;
		margin:0px;
		padding:0px;
		font-size: 13px;
	}

	.procut_item {
		width: 100%;
		margin-right: auto;
		margin-left: auto;
		padding: 20px;
		background: #F1F1F1;
		margin-bottom: 1px;
		border-radius: 5px;
	}

	.channel_item {width: 100%;margin-right: auto;margin-left: auto;padding: 20px 0 20px 0;background: #F1F1F1; border-top:solid 2px #00CC00;border-bottom:solid 2px #00CC00;margin-bottom: 1px;font-size: 12px; font-weight:bolder}
	.procut_item h4 {margin: 0px;padding: 0px;font-size: 20px;}
	.channel_item h4 {margin: 0px;padding: 0 0 10px 0;font-size: 14px;}
	.input{font-size:22px; padding:1px}
	.dw_button{font-size:16px}

	a {
		color: rgb(61, 12, 234);
		text-decoration: underline;
	}

	a:hover {
		color: rgb(54, 36, 117);
		text-decoration: underline;
	}

	.pageHeaderFacade {
		background-image: url("header.png");
		background-color: rgba(58, 109, 156, 1);
		background-size: cover;
		background-position: center top;
		background-repeat: no-repeat;
		min-height: 260px;
		height: 260px;
		max-height: 260px;
	}

	.layoutBoundary {
		min-width: 90%;
		width: 90%;
		max-width: 90%;
		padding: 0px 0px;
	}

	.pageHeaderLogo {
		height: 30px !important;
		width: 100% !important;
		text-align: center;
	}

	@media screen and (max-width:1024px){
		.pageHeaderLogo .pageHeaderLogoLarge{
			display:none
		}

		.pageHeaderLogo .pageHeaderLogoSmall{
			max-height:30px;max-width:100%
		}
	}

	@media screen and (min-width:1025px),print{
		.pageHeaderLogo{flex:1 1 auto}.pageHeaderLogo .pageHeaderLogoLarge{
			max-width:100%
		}

		.pageHeaderLogo .pageHeaderLogoSmall{
			display:none
		}

		.pageHeaderLogo > a{
			display:block;padding:10px 0
		}
	}

	.pageNavigation {
		background-color: rgba(58, 109, 156, 1);
		flex: 0 0 auto;
		padding: 0px 0px;
		min-width: 100%;
		max-width: 100%;
		height: 40px;
	}
	-->
	</style>
</head>

<body>
<div id="pageHeaderFacade" class="pageHeaderFacade">
	<div class="layoutBoundary">
		<div id="pageHeaderLogo" class="pageHeaderLogo">
			<a href="<?=$WebsiteUrl ?>">
				<img src="<?=$pageHeaderLogoLarge ?>" alt="" class="pageHeaderLogoLarge" style="width: 350px;height: 165px">
				<img src="<?=$pageHeaderLogoSmall ?>" alt="" class="pageHeaderLogoSmall">
			</a>
		</div>
	</div>
</div>
<div class="pageNavigation">
	<div class="layoutBoundary">
	</div>
</div>

<div style="padding-bottom:5px; padding-top:15px; padding-left:20px; padding-right:20px; font-size:24px; font-weight:bolder"><?=$WebsiteTitle ?> - Spenden Disclaimer</div>
<div class="product_wrapper" style="padding-bottom:8px; padding-left:20px; padding-right:20px;">
<!-- Insert Disclaimer here -->
<br>
</div>
</body>
</html>
