/**
 * Created by Jenny on 2018/4/10.
 */
$(function () {

    $(".go-page").on("click", function () {
        var _pageLink = window.location.href,
            link_arr_one = _pageLink.split('?'),
            link_arr = [];
        if(link_arr_one.length>1){
            link_arr = link_arr_one[1].split('&');
        }
        var pageLink = _pageLink,
            _pageInput = $("input[name=page]"),
            goPage = _pageInput.val();
        _pageMax = _pageInput.attr('max');
        if(goPage == ''){
            return true;
        }
        if(parseInt(goPage) <= 0){
            goPage = 1;
            _pageInput.val(goPage);
        }
        if(parseInt(goPage) > parseInt(_pageMax)){
            goPage = _pageMax;
            _pageInput.val(_pageMax);
        }
        if(link_arr.length > 0){

            for(i in link_arr){
                var link_arr1 = link_arr[i].split('=');
                if(link_arr1[0] == 'page'){
                    link_arr1[1] = goPage;
                    link_arr[i] = link_arr1.join('=');
                }
            }

            if($.inArray('page='+goPage,link_arr) == -1){
                link_arr.push('page='+goPage);
            }
            link_arr = link_arr.join('&');
            pageLink = link_arr_one[0]+'?'+link_arr;
        }else{
            link_arr.push($('form').serialize()+'&page='+goPage);
            link_arr = link_arr.join('&');
            pageLink = link_arr_one[0]+'?'+link_arr;
        }
        window.location.href = pageLink;
    });

    $(".page-size-input").on("blur", function () {
        var _pageLink = window.location.href,
            link_arr_one = _pageLink.split('?'),
            link_arr = [];
        if(link_arr_one.length>1){
            link_arr = link_arr_one[1].split('&');
        }
        var pageLink = _pageLink,
            _pageSizeInput = $("input[name=per-page]"),
            pageSize = _pageSizeInput.val(),
            _pageSizeMax = _pageSizeInput.attr('max');
        if(pageSize == ''){
            return true;
        }
        if(parseInt(pageSize) <= 0){
            pageSize = 1;
            _pageSizeInput.val(pageSize);
        }
        if(parseInt(pageSize) > parseInt(_pageSizeMax)){
            pageSize = _pageSizeMax;
            _pageSizeInput.val(_pageSizeMax);
        }
        if(link_arr.length > 0){

            for(i in link_arr){
                if(link_arr[i].indexOf('per-page') > -1){
                    link_arr[i] = 'per-page='+pageSize;
                }
            }

            if($.inArray('per-page='+pageSize,link_arr) == -1){
                link_arr.push('per-page='+pageSize);
            }
            link_arr = link_arr.join('&');
            pageLink = link_arr_one[0]+'?'+link_arr;
        }else{
            link_arr.push($('form').serialize()+'&per-page='+pageSize);
            link_arr = link_arr.join('&');
            pageLink = link_arr_one[0]+'?'+link_arr;
        }
        window.location.href = pageLink;
    });

});
