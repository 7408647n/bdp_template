/**
 * Pure cookie-consent state logic, factored out of om.ts so it can be
 * unit tested with bun test without touching the DOM or document.cookie.
 * The wire format matches the original om-cookie-manager cookie value:
 * a comma separated list of "<groupKey>.<0|1>" pairs, terminated by the
 * literal token "dismiss".
 */
export interface GroupChoice {
  group: string;
  accepted: boolean;
}

/** Parses the raw `omCookieConsent` cookie value into per-group choices. */
export function parseConsentCookie(value: string | null | undefined): GroupChoice[] {
  if (!value) return [];

  return value
    .split(',')
    .filter((part) => part && part !== 'dismiss')
    .map((part) => {
      const [group, flag] = part.split('.');
      return { group, accepted: parseInt(flag, 10) === 1 };
    });
}

/** Serializes group choices back into the cookie's wire format. */
export function serializeConsentCookie(choices: GroupChoice[]): string {
  const body = choices.map((c) => `${c.group}.${c.accepted ? 1 : 0}`).join(',');
  return body.length ? `${body},dismiss` : 'dismiss';
}

export type PanelAction = 'all' | 'save' | 'min';

export interface CheckboxState {
  value: string;
  checked: boolean;
  essential: boolean;
}

/**
 * Given the panel action button that was pressed and the current checkbox
 * states, returns the resulting group choices and the checkbox `checked`
 * states that should be applied back to the DOM.
 */
export function resolvePanelAction(
  action: PanelAction,
  checkboxes: CheckboxState[]
): { choices: GroupChoice[]; checked: Record<string, boolean> } {
  const choices: GroupChoice[] = [];
  const checked: Record<string, boolean> = {};

  for (const checkbox of checkboxes) {
    let accepted: boolean;

    switch (action) {
      case 'all':
        accepted = true;
        break;
      case 'min':
        accepted = checkbox.essential;
        break;
      case 'save':
      default:
        accepted = checkbox.checked;
        break;
    }

    choices.push({ group: checkbox.value, accepted });
    checked[checkbox.value] = accepted;
  }

  return { choices, checked };
}

/**
 * True when the stored consent cookie is missing a group that is present
 * in the panel (e.g. a new cookie group was added after the visitor last
 * decided), meaning the panel should be shown again.
 */
export function hasUnknownGroups(stored: GroupChoice[], panelGroupKeys: string[]): boolean {
  const storedKeys = new Set(stored.map((c) => c.group));
  return panelGroupKeys.some((key) => !storedKeys.has(key));
}
