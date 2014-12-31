   $(document).ready(function() {
        	$("#mainphoto").elevateZoom({scrollZoom : true, borderSize: 1,
        		easing:true,
        		borderColour:'#f00',
        		tintColour:'#ff0000',
        		cursor: 'pointer',
        		imageCrossfade: true, 
        		zoomWindowHeight: 200, 
        		zoomWindowWidth:200,
        		loadingIcon: '../../../images/loading.gif',
                zoomWindowPosition: 1, /*zoomWindowOffetx: 3*/
        	});
			//end gallery fancybox
            var imagewidth = $('.slider-large-image li').outerWidth();
            var imagesum = $('.slider-large-image li img').size();
            var imagereelwidth = imagewidth * imagesum;
            $(".slider-large-image").css({'width' : imagereelwidth});
            $('.slider-large-image li:first').before($('.slider-large-image li:last'));
            //$('.slider-large-image').css({'left' : '-' + imagewidth + 'px'});
            rotatef = function (imagewidth) {
                var left_indent = parseInt($('.slider-large-image').css('left')) - imagewidth;
                $('.slider-large-image:not(:animated)').animate({'left' : left_indent}, 500, function() {
                    $('.slider-large-image li:last').after($('.slider-large-image li:first'));
                    //$('.slider-large-image').css({'left' : '-' + imagewidth + 'px'});
                });
            };
            rotateb = function (imagewidth) {
                var left_indent = parseInt($('.slider-large-image').css('left')) + imagewidth;       
                $('.slider-large-image:not(:animated)').animate({'left' : left_indent}, 500, function(){               
                    $('.slider-large-image li:first').before($('.slider-large-image li:last'));
                    //$('.slider-large-image').css({'left' : '-' + imagewidth + 'px'});
                });
            };
            $(".slider-pager a#b").click(function () {
                rotateb(imagewidth);
                return false;
            });
            $(".slider-pager a#f").click(function () {
                rotatef(imagewidth);
                return false;
            });

            $(".slider-large-image li img").click(function() {
            	//change image source
                $(".main-photo img").attr("src", $(this).attr('src'));
                //load zoom
                $("#mainphoto").elevateZoom({scrollZoom : true, borderSize: 1,
                    easing:true,
                    borderColour:'#f00',
                    tintColour:'#ff0000',
                    cursor: 'pointer',
                    imageCrossfade: true, 
                    zoomWindowHeight: 200, 
                    zoomWindowWidth:200 ,
                    loadingIcon: '../../../img/loading.gif',
                    zoomWindowPosition: 1,/* zoomWindowOffetx: 3*/
                });
            });
        });