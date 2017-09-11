/*global $, jQuery, alert, console*/

var Dashboard = function () {
    'use strict';
    //--------------
    
    this.__construct = function () {
        console.log('dashboard created');
        
        // setActiveSection();

        Lists_Templates = new Lists_Templates();
        Forms_Templates = new Forms_Templates();

        Events = new Events();
        Result = new Result();
//        Tests = new Tests();
        
        //loadTestCard();
        
    };
    
    //-----------
    
    // var setActiveSection = function () {

    //     console.log('setting active section');

    //     console.log(location.hash);

    //     var firstElement = $('ul.side-nav').children(':eq(1)'),
    //         notCollapsibleHeader = '.side-nav a:not(.collapsible-header)',
    //         collapsibleHeader = '.side-nav a.collapsible-header',
    //         currTag = location.hash,
    //         tags = $(notCollapsibleHeader).map(function (v) {
    //             if ($(this).attr('id') === currTag.split('#').pop()) {
    //                 console.log('tag is valid');
    //                 return true;
    //             } else {
    //                 console.log('tag is NOT valid');
    //                 return false;
    //             }
    //         }),
    //         tabActivates,
    //         id,
    //         pageTitle,
    //         tagExists = true;

    //     console.log(jQuery.inArray(true, tags));

    //     //if there's a true value in tags array, the tab exists
    //     if (jQuery.inArray(true, tags) === -1) {
    //         tagExists = false;
    //     }

    //     if (currTag.split('')[1] === '!') {
    //         console.log(currTag.split('')[1]);
    //         tagExists = true;
    //         currTag = '';//just clear just incase
    //         console.log('clearing');
    //     }

    //     if (!currTag || currTag === '#' || tagExists === false) {
    //         switch (firstElement[0].nodeName) {
    //         case 'UL':
    //             firstElement.children('li:eq(0)')
    //                 .addClass('active')
    //                 .find('a.collapsible-header').addClass('active')
    //                 .find('.collapsible-body li:nth(0)').addClass('active');

    //             tabActivates = firstElement.children('li:eq(0)').find('.collapsible-body li:nth(0) a:eq(0)').attr('data-activates');
    //             id = firstElement.children('li:eq(0)').find('.collapsible-body li:nth(0) a:eq(0)').attr('id');
    //             location.hash = id;
    //             pageTitle = firstElement.children('li:eq(0)').find('.collapsible-body li:nth(0) a:eq(0)').text();

    //             console.log(tabActivates);
    //             console.log(id);

    //             break;
    //         case 'LI':
    //             firstElement.addClass('active');

    //             tabActivates = firstElement.children('a').attr('data-activates');
    //             id = firstElement.children('a').attr('id');
    //             location.hash = id;
    //             pageTitle = firstElement.children('a').text();

    //             break;
    //         default:
    //             break;

    //         }

    //     } else {

    //         //clear all active items in side-nav
    //         $(notCollapsibleHeader).removeClass('active')
    //             .parent().removeClass('active');//clean out any active class
    //         $(collapsibleHeader).removeClass('active')
    //             .parent().removeClass('active')//clean out any active class
    //             .find('.collapsible-body').css('display', 'none');//close all collapsible bodies;

    //         pageTitle = $(notCollapsibleHeader + currTag).text();
    //         tabActivates = $(notCollapsibleHeader + currTag).attr('data-activates');

    //         console.log('was already set to ' + tabActivates);

    //         $(notCollapsibleHeader + currTag).parent('li').addClass('active');// make active the tag that's similar to the location hash
    //         $(notCollapsibleHeader + currTag).parent('li').parent().parent('.collapsible-body').parent().addClass('active');// make active the collapsible header of the tag that's similar to the location hash
    //         $(notCollapsibleHeader + currTag).parent('li').parent().parent().parent().parent('.collapsible').find('.collapsible-header').addClass('active');// make active the collapsible header of the tag that's similar to the location hash
    //         $(notCollapsibleHeader + currTag).parent('li').parent().parent('.collapsible-body').css('display', 'block');// Open the collapsible body

    //     }

    //     //hide all tabs in <main> then remove 'hide' class on the active bar
    //     $('main .main-tab').addClass('hide').removeClass('active-bar');
    //     $('main #' + tabActivates).addClass('active-bar new-class');
    //     $('main .active-bar').removeClass('hide');

    //     //change the page title just incase
    //     $('a#pageTitle').text(pageTitle);

    //     $(document).on('click', '.side-nav a', function (e) {
    //         if ($(this).attr('id')) {
    //             location.hash = $(this).attr('id');//add the id to the url as hash '#'
    //         }
    //     });

    //             /********** ON CLICK EVENT **********/

    //     //on click none-collapsible-headers side-nav panel actions
    //     $(notCollapsibleHeader).click(function (e) {

    //         //console.log($(this).attr('data-activates'));

    //         var activates = $(this).attr('data-activates'),
    //             pageTitle = $(this).text();

    //         //if it's not the name in the side nav that is clicked, switch tabs
    //         if ($(this).attr('id') !== 'name' && $(this).attr('id') !== undefined) {

    //             e.preventDefault();

    //             $(notCollapsibleHeader).parent().removeClass('active');
    //             if (!$(this).parent().parent().parent().hasClass('collapsible-body')) {
    //                 //console.log('is a collapsible list');
    //                 $(notCollapsibleHeader).parent().removeClass('active');

    //             } //else {
    //                 //console.log('not a collapsible list');

    //            // }
    //             $(this).parent().addClass('active');

    //             $('main .active-bar').addClass('hide');

    //             $('main .main-tab').removeClass('active-bar');

    //             $('main #' + activates).addClass('active-bar');

    //             $('main .active-bar').removeClass('hide');

    //             console.log('bar-clicked');
    //             console.log('setting header text');

    //             $('a#pageTitle').text(pageTitle);

    //         } else {

    //             console.log('name clicked');
    //             console.log('undefined---doing nothing');

    //         }

    //     });

    //     /**************
    //     TABS SWITCH FUNCTIONALITIES END
    //     ************/

    // };

    //-------------
    
    this.__construct();
    
};
