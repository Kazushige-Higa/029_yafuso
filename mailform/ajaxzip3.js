/*
 * AjaxZip3-compatible postal-code helper.
 *
 * The original library loaded third-party JSONP as executable script. This
 * version keeps the public zip2addr/callback API but requests JSON through
 * the same-origin mailform/zipcode.php proxy instead.
 */
(function (global) {
    'use strict';

    var AjaxZip3 = global.AjaxZip3 = global.AjaxZip3 || {};
    AjaxZip3.VERSION = '0.51-yafuso';
    AjaxZip3.JSONDATA = '/mailform/zipcode.php?zip=';
    AjaxZip3.CACHE = AjaxZip3.CACHE || {};
    AjaxZip3.ffocus = true;
    AjaxZip3.onSuccess = null;
    AjaxZip3.onFailure = null;

    AjaxZip3.getElementByName = function (name, base) {
        if (typeof name !== 'string') return name;
        var elements = document.getElementsByName(name);
        if (!elements.length) return null;
        if (elements.length > 1 && base && base.form) {
            for (var i = 0; i < base.form.elements.length; i++) {
                if (base.form.elements[i].name === name) return base.form.elements[i];
            }
        }
        return elements[0];
    };

    AjaxZip3.zip2addr = function (zip1Name, zip2Name, prefName, addrName, areaName, streetName, focus) {
        AjaxZip3.fzip1 = AjaxZip3.getElementByName(zip1Name);
        AjaxZip3.fzip2 = AjaxZip3.getElementByName(zip2Name, AjaxZip3.fzip1);
        AjaxZip3.fpref = AjaxZip3.getElementByName(prefName, AjaxZip3.fzip1);
        AjaxZip3.faddr = AjaxZip3.getElementByName(addrName, AjaxZip3.fzip1);
        AjaxZip3.farea = AjaxZip3.getElementByName(areaName, AjaxZip3.fzip1);
        AjaxZip3.fstrt = AjaxZip3.getElementByName(streetName, AjaxZip3.fzip1);
        AjaxZip3.ffocus = focus === undefined ? true : focus;
        if (!AjaxZip3.fzip1 || !AjaxZip3.fpref || !AjaxZip3.faddr) return;

        var zip = String(AjaxZip3.fzip1.value || '') + String(AjaxZip3.fzip2 ? AjaxZip3.fzip2.value || '' : '');
        AjaxZip3.nzip = zip.replace(/[^0-9]/g, '');
        if (AjaxZip3.nzip.length < 7) return;

        if (AjaxZip3.CACHE[AjaxZip3.nzip]) {
            AjaxZip3.callback(AjaxZip3.CACHE[AjaxZip3.nzip]);
            return;
        }

        fetch(AjaxZip3.JSONDATA + encodeURIComponent(AjaxZip3.nzip), {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' }
        }).then(function (response) {
            if (!response.ok) throw new Error('Postal lookup failed');
            return response.json();
        }).then(function (data) {
            AjaxZip3.CACHE[AjaxZip3.nzip] = data;
            AjaxZip3.callback(data);
        }).catch(function () {
            if (typeof AjaxZip3.onFailure === 'function') AjaxZip3.onFailure();
        });
    };

    AjaxZip3.callback = function (data) {
        var record = data && (data[AjaxZip3.nzip] || data[String(Number(AjaxZip3.nzip) + 4278190080)]);
        if (!record || !record[0]) {
            if (typeof AjaxZip3.onFailure === 'function') AjaxZip3.onFailure();
            return;
        }

        var prefCode = String(record[0]);
        var pref = record[1] || '';
        var city = record[2] || '';
        var town = record[3] || '';
        var addressField = AjaxZip3.faddr;

        if (AjaxZip3.fpref.type === 'select-one' || AjaxZip3.fpref.type === 'select-multiple') {
            for (var i = 0; i < AjaxZip3.fpref.options.length; i++) {
                var option = AjaxZip3.fpref.options[i];
                option.selected = option.value === prefCode || option.value === pref || option.text === pref;
            }
        } else if (AjaxZip3.fpref.name === AjaxZip3.faddr.name) {
            addressField.value = pref + city;
        } else {
            AjaxZip3.fpref.value = pref;
            addressField.value = city;
        }

        if (AjaxZip3.farea) {
            AjaxZip3.farea.value = town;
            addressField = AjaxZip3.farea;
        } else if (AjaxZip3.fpref.name === AjaxZip3.faddr.name) {
            addressField.value += town;
        }
        if (AjaxZip3.fstrt) {
            AjaxZip3.fstrt.value = AjaxZip3.faddr.name === AjaxZip3.fstrt.name
                ? AjaxZip3.fstrt.value + town
                : (AjaxZip3.fstrt.value || '');
            addressField = AjaxZip3.fstrt;
        }

        if (typeof AjaxZip3.onSuccess === 'function') AjaxZip3.onSuccess();
        if (!AjaxZip3.ffocus || !addressField || !addressField.value) return;
        addressField.focus();
        if (typeof addressField.setSelectionRange === 'function') {
            var length = addressField.value.length;
            addressField.setSelectionRange(length, length);
        }
    };

    global.$yubin = function (data) { AjaxZip3.callback(data); };
}(window));
