<?php $prefix = "_mneylink_".generate_random_string() ?>
<?php if (!empty($jobtf)) : ?>
var jobtfs<?=$prefix?> = <?php echo json_encode($jobtf) ?>;
<?php else: ?>
var jobtfs<?=$prefix?> = [];
<?php endif; ?>
<?php if(!empty($jobtf)){$mh = md5($jobtf[0]['id']);} else $mh = md5(generate_random_string());  ?>
var cl_url<?=$prefix?> = window.location.href;
var current<?=$prefix?> = [];
var step<?=$prefix?> = 1;
var traffic_time<?=$prefix?> = <?php echo (!empty($traffics_settings)) ? $traffics_settings : 60 ?>;
var traffic2_time<?=$prefix?> = <?php echo (!empty($traffics2_settings)) ? $traffics2_settings : 80 ?>;
var traffic2_url_time<?=$prefix?> = <?php echo (!empty($traffics2_url_settings)) ? $traffics2_url_settings : 10 ?>;

var rf<?=$prefix?> = document.referrer;
bu<?=$prefix?> = '<?php echo build_main_domain_url('/') ?>';
wID<?=$prefix?> = location.href;
wID2<?=$prefix?> = '';
vip<?=$prefix?> = 0;
brs<?=$prefix?> = ['google.com'];
fl<?=$prefix?> = 0;
fl<?=$prefix?> = ck<?=$prefix?>(rf<?=$prefix?>, brs<?=$prefix?>);
el<?=$prefix?> = document.getElementById('brv-mneylink-countdown');
el<?=$prefix?>.id = 'brv-mneylink-countdown-<?php echo $mh; ?>';

jobtfs<?=$prefix?>.forEach(function (i){
    if(cl_url<?=$prefix?>.indexOf(i.link_url) >= 0){
        current<?=$prefix?> = i;
        step<?=$prefix?> = i.step;
    }
})

var dt<?=$prefix?> = (Object.keys(current<?=$prefix?>).length) ? current<?=$prefix?>.id : 0;
var s<?=$prefix?> = traffic_time<?=$prefix?>;
if(current<?=$prefix?>.traffic_ver2){
    if(step<?=$prefix?> === 2){
        s<?=$prefix?> = traffic2_url_time<?=$prefix?>;
    } else {
        s<?=$prefix?> = traffic2_time<?=$prefix?>;
    }
}

if(step<?=$prefix?> === 1){
    if(fl<?=$prefix?>) crtbn<?=$prefix?>();
} else {
    wID<?=$prefix?> = current<?=$prefix?>.link_url;
    wID2<?=$prefix?> = current<?=$prefix?>.traffic2_url;
    crtbn<?=$prefix?>();
}

function crtbn<?=$prefix?>(){
    var bt<?=$prefix?> = document.createElement('div');
    var fn<?=$prefix?> = 0;
    bt<?=$prefix?>.innerHTML = '<span style="display:inline-block;vertical-align:middle">LẤY MÃ NGAY | mneylink.com </span>';
    bt<?=$prefix?>.style.fontSize = '16px';
    bt<?=$prefix?>.style.background = '#E95950';
    bt<?=$prefix?>.style.border = '1px solid #fff';
    bt<?=$prefix?>.style.color = '#fff';
    bt<?=$prefix?>.style.fontWeight = '700';
    bt<?=$prefix?>.style.borderRadius = '7px';
    bt<?=$prefix?>.style.padding = '7px 20px';
    bt<?=$prefix?>.style.margin = '5px';
    bt<?=$prefix?>.style.minHeight = 'auto';
    bt<?=$prefix?>.style.minWidth = '170px';
    bt<?=$prefix?>.style.lineHeight = '20px';
    bt<?=$prefix?>.style.verticalAlign = 'middle';
    bt<?=$prefix?>.style.cursor = 'pointer';
    bt<?=$prefix?>.style.display = 'inline-block';
    bt<?=$prefix?>.style.textAlign = 'center';
    bt<?=$prefix?>.style.fontFamily = 'Arial,sans-serif';
    bt<?=$prefix?>.id = 'brv-get-code';
    el<?=$prefix?>.appendChild(bt<?=$prefix?>);
    if(step<?=$prefix?> === 2){
        if(fn<?=$prefix?> === 0) gC<?=$prefix?>(); fn<?=$prefix?> = 1;
    } else {
    bt<?=$prefix?>.addEventListener('click', function () {
        detectIncognito().then((n=>{
            if(n.isPrivate){
                if(!bt<?=$prefix?>.classList.contains('btn-disable')){
                    alert('Vui lòng tắt chế độ ẩn danh');
                }
                bt<?=$prefix?>.classList.add("btn-disable");
                bt<?=$prefix?>.style.cursor = 'not-allowed';
                bt<?=$prefix?>.style.opacity = '0.5';
            } else {
                if(fn<?=$prefix?> === 0){
                    this.innerHTML = 'VUI LÒNG ĐỢI ...'+s<?=$prefix?>;
                    gC<?=$prefix?>();
                    fn<?=$prefix?> = 1;
                    return false
                }
            }
        }));
    });
    }
    bt<?=$prefix?>.addEventListener('mouseenter', (x10) => {
        bt<?=$prefix?>.style.background = '#c40b11'
    });
    bt<?=$prefix?>.addEventListener('mouseleave', (x10) => {
        bt<?=$prefix?>.style.background = '#E95950'
    });
    bt<?=$prefix?>.addEventListener('mousedown', (x10) => {
        bt<?=$prefix?>.style.background = '#9a070d'
    });
    bt<?=$prefix?>.addEventListener('mouseup', (x10) => {
        bt<?=$prefix?>.style.background = '#E95950'
    })
}

function gC<?=$prefix?>(){
    cd<?=$prefix?>();
    var active<?=$prefix?> = 1;
    document.addEventListener('visibilitychange',() => {
        active<?=$prefix?> = (document.visibilityState === 'visible') ? 1 : 0;
    })
    setTimeout(function countdown<?=$prefix?>() {
        if(active<?=$prefix?> === 1) s<?=$prefix?>--;
        el<?=$prefix?>.childNodes[0].innerHTML = 'VUI LÒNG ĐỢI ...'+s<?=$prefix?>;
        if (s<?=$prefix?>>0) setTimeout(countdown<?=$prefix?>, 1000)
        else {
            if(current<?=$prefix?>.traffic_ver2 && step<?=$prefix?> === 1){
                const data<?=$prefix?> = updateStep<?=$prefix?>(current<?=$prefix?>.id);
                el<?=$prefix?>.childNodes[0].innerHTML = data<?=$prefix?>.message;
            } else {
                let data<?=$prefix?> = ltr<?=$prefix?>();
                el<?=$prefix?>.childNodes[0].innerHTML = (data<?=$prefix?>.status) ? data<?=$prefix?>.data.html : data<?=$prefix?>.message;
            }
        }
    }, 1000);
}

function cd<?=$prefix?>(){
    var xhttp<?=$prefix?> = new XMLHttpRequest();
    var url<?=$prefix?> = bu<?=$prefix?>+'cd?&t='+traffic_time<?=$prefix?>;
    xhttp<?=$prefix?>.open("GET", url<?=$prefix?>, false);
    xhttp<?=$prefix?>.send();
    return JSON.parse(xhttp<?=$prefix?>.response);
}

function ltr<?=$prefix?>(){
    var xhttp<?=$prefix?> = new XMLHttpRequest();
    var url<?=$prefix?> = bu<?=$prefix?>+'load_traffic?&r='+rf<?=$prefix?>+'&w='+wID<?=$prefix?>+'&t='+traffic_time<?=$prefix?>+'&ti='+dt<?=$prefix?>;
    if(step<?=$prefix?> === 2) url<?=$prefix?> = bu<?=$prefix?>+'load_traffic?&r='+rf<?=$prefix?>+'&w='+wID<?=$prefix?>+'&w2='+wID2<?=$prefix?>+'&t='+traffic2_url_time<?=$prefix?>+'&ti='+dt<?=$prefix?>;
    xhttp<?=$prefix?>.open("GET", url<?=$prefix?>, false);
    xhttp<?=$prefix?>.send();
    return JSON.parse(xhttp<?=$prefix?>.response);
}

function updateStep<?=$prefix?>(id<?=$prefix?>){
    var xhttp<?=$prefix?> = new XMLHttpRequest();
    var url<?=$prefix?> = bu<?=$prefix?>+'update-step?id='+id<?=$prefix?>;
    xhttp<?=$prefix?>.open("GET", url<?=$prefix?>, false);
    xhttp<?=$prefix?>.send();
    return JSON.parse(xhttp<?=$prefix?>.response);
}


function ck<?=$prefix?>(e<?=$prefix?>, r<?=$prefix?>) {
    var f<?=$prefix?> = 0;
    return r<?=$prefix?>.forEach(function(x, i) {
        -1 !== e<?=$prefix?>.indexOf(x) && (f<?=$prefix?> = 1)
    }), f<?=$prefix?>
}

!function() {var e = {};!function() {"use strict";var t = e;t.detectIncognito = void 0,t.detectIncognito = function() {return new Promise((function(e, t) {var o, n, r = "Unknown";function i(t) {e({isPrivate: t,browserName: r})}function a(e) {return e === eval.toString().length}void 0 !== (n = navigator.vendor) && 0 === n.indexOf("Apple") && a(37) ? (r = "Safari",void 0 !== navigator.maxTouchPoints ? function() {var e = String(Math.random());try {window.indexedDB.open(e, 1).onupgradeneeded = function(t) {var o, n, r = null === (o = t.target) || void 0 === o ? void 0 : o.result;try {r.createObjectStore("test", {autoIncrement: !0}).put(new Blob),i(!1)} catch (e) {var a = e;return e instanceof Error && (a = null !== (n = e.message) && void 0 !== n ? n : e),i("string" == typeof a && /BlobURLs are not yet supported/.test(a))} finally {r.close(),window.indexedDB.deleteDatabase(e)}}} catch (e) {return i(!1)}}() : function() {var e = window.openDatabase, t = window.localStorage;try {e(null, null, null, null)} catch (e) {return i(!0)}try {t.setItem("test", "1"),t.removeItem("test")} catch (e) {return i(!0)}i(!1)}()) : function() {var e = navigator.vendor;return void 0 !== e && 0 === e.indexOf("Google") && a(33)}() ? (o = navigator.userAgent,r = o.match(/Chrome/) ? void 0 !== navigator.brave ? "Brave" : o.match(/Edg/) ? "Edge" : o.match(/OPR/) ? "Opera" : "Chrome" : "Chromium",void 0 !== self.Promise && void 0 !== self.Promise.allSettled ? navigator.webkitTemporaryStorage.queryUsageAndQuota((function(e, t) {var o;i(Math.round(t / 1048576) < 2 * Math.round((void 0 !== (o = window).performance && void 0 !== o.performance.memory && void 0 !== o.performance.memory.jsHeapSizeLimit ? performance.memory.jsHeapSizeLimit : 1073741824) / 1048576))}), (function(e) {t(new Error("detectIncognito somehow failed to query storage quota: " + e.message))})) : (0,window.webkitRequestFileSystem)(0, 1, (function() {i(!1)}), (function() {i(!0)}))) : void 0 !== document.documentElement && void 0 !== document.documentElement.style.MozAppearance && a(37) ? (r = "Firefox",i(void 0 === navigator.serviceWorker)) : void 0 !== navigator.msSaveBlob && a(39) ? (r = "Internet Explorer",i(void 0 === window.indexedDB)) : t(new Error("detectIncognito cannot determine the browser"))}))}}(),detectIncognito = e.detectIncognito}();