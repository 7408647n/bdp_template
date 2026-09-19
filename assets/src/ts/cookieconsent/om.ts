import {
  parseConsentCookie,
  serializeConsentCookie,
  resolvePanelAction,
  hasUnknownGroups,
  type PanelAction,
} from './state';

/**
 * Ported from Resources/Private/Source/JavaScript/cookieconsent/om.js
 * (originally provided by the TYPO3 extension opfaff/om-cookie-manager).
 * DOM wiring lives here; the pure consent-cookie logic lives in state.ts.
 */

interface CookieGroupAsset {
  header?: string[];
  body?: string[];
  gtm?: string;
  [key: string]: unknown;
}

type CookieGroups = Record<string, Record<string, CookieGroupAsset>>;

let omCookieGroups: CookieGroups = {};
const omGtmEvents: string[] = [];

try {
  const groupsNode = document.getElementById('om-cookie-consent');
  omCookieGroups = groupsNode ? JSON.parse(groupsNode.innerHTML) : {};
} catch (err) {
  // eslint-disable-next-line no-console
  console.log(
    'OM Cookie Manager: No Cookie Groups found! Maybe you have forgot to set the page id inside the constants of the extension'
  );
}

const omCookieUtility = {
  getCookie(name: string): string | null {
    const v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    return v ? v[2] : null;
  },
  setCookie(name: string, value: string, days: number): void {
    const d = new Date();
    d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * days);
    document.cookie = `${name}=${value};path=/;expires=${d.toUTCString()};SameSite=Lax`;
  },
};

function omCookieEnableCookieGrp(groupKey: string): void {
  const group = omCookieGroups[groupKey];
  if (group === undefined) return;

  for (const key of Object.keys(group)) {
    const obj = group[key];

    if (key === 'gtm') {
      const gtm = (obj as unknown as string) || (group.gtm as unknown as string);
      if (gtm) omGtmEvents.push(gtm as unknown as string);
      continue;
    }

    for (const prop of Object.keys(obj)) {
      const value = obj[prop];
      if (Array.isArray(value)) {
        const content = value.join('');
        const range = document.createRange();
        if (prop === 'header') {
          range.selectNode(document.getElementsByTagName('head')[0]);
          document.getElementsByTagName('head')[0].appendChild(range.createContextualFragment(content));
        } else {
          range.selectNode(document.body);
          document.body.appendChild(range.createContextualFragment(content));
        }
      }
    }
  }

  delete omCookieGroups[groupKey];
}

function pushGtmEvents(events: string[]): void {
  window.dataLayer = window.dataLayer || [];
  events.forEach((event) => {
    window.dataLayer?.push({ event });
  });
}

function omTriggerPanelEvent(events: string[]): void {
  const panel = document.querySelectorAll('[data-omcookie-panel]')[0];
  if (!panel) return;
  events.forEach((event) => {
    panel.dispatchEvent(new CustomEvent(event, { bubbles: true }));
  });
}

function readCheckboxes(): { value: string; checked: boolean; essential: boolean }[] {
  return Array.from(document.querySelectorAll<HTMLInputElement>('[data-omcookie-panel-grp]')).map(
    (checkbox) => ({
      value: checkbox.value,
      checked: checkbox.checked,
      essential: checkbox.getAttribute('data-omcookie-panel-essential') !== null,
    })
  );
}

function omCookieSaveAction(this: HTMLElement): void {
  const action = this.getAttribute('data-omcookie-panel-save') as PanelAction | null;
  if (!action) return;

  const checkboxElements = document.querySelectorAll<HTMLInputElement>('[data-omcookie-panel-grp]');
  const { choices, checked } = resolvePanelAction(action, readCheckboxes());

  choices.forEach((choice) => {
    if (choice.accepted) {
      omCookieEnableCookieGrp(choice.group);
    }
  });

  checkboxElements.forEach((checkbox) => {
    if (checkbox.value in checked) {
      checkbox.checked = checked[checkbox.value];
    }
  });

  omCookieUtility.setCookie('omCookieConsent', serializeConsentCookie(choices), 364);
  pushGtmEvents(omGtmEvents);
  omTriggerPanelEvent(['cookieconsentsave', 'cookieconsentscriptsloaded']);

  setTimeout(() => {
    document.querySelectorAll('[data-omcookie-panel]')[0]?.classList.toggle('active');
  }, 350);
}

document.addEventListener('DOMContentLoaded', function () {
  const panelButtons = document.querySelectorAll<HTMLElement>('[data-omcookie-panel-save]');
  const openButtons = document.querySelectorAll<HTMLElement>('[data-omcookie-panel-show]');
  const omCookiePanel = document.querySelectorAll<HTMLElement>('[data-omcookie-panel]')[0];
  if (omCookiePanel === undefined) return;

  let openCookiePanel = true;

  const cookieConsentData = omCookieUtility.getCookie('omCookieConsent');
  if (cookieConsentData !== null && cookieConsentData.length > 0) {
    openCookiePanel = false;
    const choices = parseConsentCookie(cookieConsentData);
    const checkboxes = document.querySelectorAll<HTMLInputElement>('[data-omcookie-panel-grp]');

    choices.forEach((choice) => {
      if (choice.accepted) {
        omCookieEnableCookieGrp(choice.group);
      }
    });

    checkboxes.forEach((checkbox) => {
      const choice = choices.find((c) => c.group === checkbox.value);
      if (choice) checkbox.checked = choice.accepted;
    });

    if (hasUnknownGroups(choices, Array.from(checkboxes).map((c) => c.value))) {
      openCookiePanel = true;
    }

    pushGtmEvents(omGtmEvents);
    omTriggerPanelEvent(['cookieconsentscriptsloaded']);
  }

  if (openCookiePanel === true) {
    setTimeout(() => {
      omCookiePanel.classList.toggle('active');
    }, 1000);
  }

  panelButtons.forEach((button) => button.addEventListener('click', omCookieSaveAction));
  openButtons.forEach((button) =>
    button.addEventListener('click', () => {
      omCookiePanel.classList.toggle('active');
    })
  );
});
