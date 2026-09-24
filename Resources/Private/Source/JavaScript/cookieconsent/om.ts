// omCookieGroups is dynamic, third-party JSON from the OM Cookie Consent
// TYPO3 extension: most keys hold {header?, body?} HTML-fragment arrays,
// but the 'gtm' key holds a plain string. A single precise interface
// would misrepresent that shape, so it's kept as `any` here deliberately.
let omCookieGroups: Record<string, any> = {};
let omGtmEvents: string[] = [];

try {
    omCookieGroups = JSON.parse(document.getElementById('om-cookie-consent')!.innerHTML);
} catch (err) {
    console.log('OM Cookie Manager: No Cookie Groups found! Maybe you have forgot to set the page id inside the constants of the extension')
}


document.addEventListener('DOMContentLoaded', function () {
    const panelButtons = document.querySelectorAll<HTMLElement>('[data-omcookie-panel-save]');
    const openButtons = document.querySelectorAll<HTMLElement>('[data-omcookie-panel-show]');
    let i: number;
    const omCookiePanel = document.querySelectorAll('[data-omcookie-panel]')[0] as Element | undefined;
    if (omCookiePanel === undefined) return;
    let openCookiePanel = true;

    //Enable stuff by Cookie
    const cookieConsentData = omCookieUtility.getCookie('omCookieConsent');
    if (cookieConsentData !== null && cookieConsentData.length > 0) {
        //dont open the panel if we have the cookie
        openCookiePanel = false;
        const checkboxes = document.querySelectorAll<HTMLInputElement>('[data-omcookie-panel-grp]');
        const cookieConsentGrps = cookieConsentData.split(',');
        let cookieConsentActiveGrps = '';

        for (i = 0; i < cookieConsentGrps.length; i++) {
            if (cookieConsentGrps[i] !== 'dismiss') {
                const grpSettings = cookieConsentGrps[i].split('.');
                if (parseInt(grpSettings[1]) === 1) {
                    omCookieEnableCookieGrp(grpSettings[0]);
                    cookieConsentActiveGrps += grpSettings[0] + ',';
                }
            }
        }
        for (i = 0; i < checkboxes.length; i++) {
            // delimiters prevent 'group-1' from matching 'group-12'
            if ((',' + cookieConsentActiveGrps).indexOf(',' + checkboxes[i].value + ',') !== -1) {
                checkboxes[i].checked = true;
            }
            //check if we have a new group
            if ((',' + cookieConsentData).indexOf(',' + checkboxes[i].value + '.') === -1) {
                openCookiePanel = true;
            }
        }
        //push stored events(sored by omCookieEnableCookieGrp) to gtm. We push this last so we are sure that gtm is loaded
        pushGtmEvents(omGtmEvents);
        omTriggerPanelEvent(['cookieconsentscriptsloaded']);
    }
    if (openCookiePanel === true) {
        //timeout, so the user can see the page before he get the nice cookie panel
        setTimeout(function () {
            omCookiePanel.classList.toggle('active');
        }, 1000);
    }

    //check for button click
    for (i = 0; i < panelButtons.length; i++) {
        panelButtons[i].addEventListener('click', omCookieSaveAction, false);
    }
    for (i = 0; i < openButtons.length; i++) {
        openButtons[i].addEventListener('click', function () {
            omCookiePanel.classList.toggle('active');
        }, false);
    }

});

//activates the groups
const omCookieSaveAction = function (this: HTMLElement) {
    const action = this.getAttribute('data-omcookie-panel-save');
    const checkboxes = document.querySelectorAll<HTMLInputElement>('[data-omcookie-panel-grp]');
    let i: number;
    //check if we have a cookie
    let cookie = omCookieUtility.getCookie('omCookieConsent');
    if (cookie === null || cookie.length <= 0) {
        //set cookie to empty string when no cookie data was found
        cookie = '';
    } else {
        //reset all values inside the cookie which are present in the actual panel
        for (i = 0; i < checkboxes.length; i++) {
            const groupKey = checkboxes[i].value;
            cookie = cookie.split(',').filter(token => token.split('.')[0] !== groupKey).join(',');
        }
    }
    //save the group id (group-x) and the made choice (.0 for group denied and .1 for group accepted)
    switch (action) {
        case 'all':
            for (i = 0; i < checkboxes.length; i++) {
                omCookieEnableCookieGrp(checkboxes[i].value);
                cookie += checkboxes[i].value + '.1,';
                checkboxes[i].checked = true;
            }
            break;
        case 'save':
            for (i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked === true) {
                    omCookieEnableCookieGrp(checkboxes[i].value);
                    cookie += checkboxes[i].value + '.1,';
                } else {
                    cookie += checkboxes[i].value + '.0,';
                }
            }
            break;
        case 'min':
            for (i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].getAttribute('data-omcookie-panel-essential') !== null) {
                    omCookieEnableCookieGrp(checkboxes[i].value);
                    cookie += checkboxes[i].value + '.1,';
                } else {
                    cookie += checkboxes[i].value + '.0,';
                    checkboxes[i].checked = false;
                }
            }
            break;
    }
    //replace dismiss to the end of the cookie
    cookie = cookie.replace('dismiss', '');
    cookie += 'dismiss';
    //cookie = cookie.slice(0, -1);
    omCookieUtility.setCookie('omCookieConsent', cookie, 364);
    //push stored events to gtm. We push this last so we are sure that gtm is loaded
    pushGtmEvents(omGtmEvents);
    omTriggerPanelEvent(['cookieconsentsave', 'cookieconsentscriptsloaded']);

    setTimeout(function () {
        (document.querySelectorAll('[data-omcookie-panel]')[0] as Element).classList.toggle('active');
    }, 350)

};

const omTriggerPanelEvent = function (events: string[]) {
    events.forEach(function (event) {
        const eventObj = new CustomEvent(event, {bubbles: true});
        (document.querySelectorAll('[data-omcookie-panel]')[0] as Element).dispatchEvent(eventObj);
    })
};

const pushGtmEvents = function (events: string[]) {
    const dataLayer = window.dataLayer = window.dataLayer || [];
    events.forEach(function (event) {
        dataLayer.push({
            'event': event,
        });
    });
};
const omCookieEnableCookieGrp = function (groupKey: string) {
    if (omCookieGroups[groupKey] !== undefined) {
        for (const key in omCookieGroups[groupKey]) {
            // skip loop if the property is from prototype
            if (!Object.prototype.hasOwnProperty.call(omCookieGroups[groupKey], key)) continue;
            const obj = omCookieGroups[groupKey][key];
            //save gtm event for pushing
            if (key === 'gtm') {
                if (omCookieGroups[groupKey][key]) {
                    omGtmEvents.push(omCookieGroups[groupKey][key]);
                }
                continue;
            }
            //set the cookie html
            for (const prop in obj) {
                // skip loop if the property is from prototype
                if (!Object.prototype.hasOwnProperty.call(obj, prop)) continue;

                if (Array.isArray(obj[prop])) {
                    let content = '';
                    //get the html content
                    obj[prop].forEach(function (htmlContent: string) {
                        content += htmlContent
                    });
                    const range = document.createRange();
                    if (prop === 'header') {
                        // add the html to header
                        range.selectNode(document.getElementsByTagName('head')[0]);
                        const documentFragHead = range.createContextualFragment(content);
                        document.getElementsByTagName('head')[0].appendChild(documentFragHead);
                    } else {
                        //add the html to body
                        range.selectNode(document.getElementsByTagName('body')[0]);
                        const documentFragBody = range.createContextualFragment(content);
                        document.getElementsByTagName('body')[0].appendChild(documentFragBody);
                    }
                }
            }
        }
        //remove the group so we don't set it again
        delete omCookieGroups[groupKey];
    }
};
const omCookieUtility = {
    getCookie: function (name: string): string | null {
        const v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
        return v ? v[2] : null;
    },
    setCookie: function (name: string, value: string, days: number): void {
        const d = new Date();
        d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * days);
        document.cookie = name + "=" + value + ";path=/;expires=" + d.toUTCString() + ";SameSite=Lax";
    },
    deleteCookie: function (name: string): void {
        omCookieUtility.setCookie(name, '', -1);
    }
};

(function () {

    if (typeof (window as any).CustomEvent === "function") return false;

    function CustomEvent(event: string, params?: CustomEventInit) {
        params = params || {bubbles: false, cancelable: false, detail: null};
        const evt = document.createEvent('CustomEvent');
        evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
        return evt;
    }

    (window as any).CustomEvent = CustomEvent;
})();
