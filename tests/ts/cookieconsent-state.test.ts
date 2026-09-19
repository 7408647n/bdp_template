import { describe, expect, test } from 'bun:test';
import {
  parseConsentCookie,
  serializeConsentCookie,
  resolvePanelAction,
  hasUnknownGroups,
} from '../../assets/src/ts/cookieconsent/state';

describe('parseConsentCookie', () => {
  test('returns an empty array for null/undefined/empty input', () => {
    expect(parseConsentCookie(null)).toEqual([]);
    expect(parseConsentCookie(undefined)).toEqual([]);
    expect(parseConsentCookie('')).toEqual([]);
  });

  test('parses group.flag pairs and ignores the dismiss token', () => {
    expect(parseConsentCookie('group-essential.1,group-statistics.0,dismiss')).toEqual([
      { group: 'group-essential', accepted: true },
      { group: 'group-statistics', accepted: false },
    ]);
  });
});

describe('serializeConsentCookie', () => {
  test('joins choices and appends dismiss', () => {
    expect(
      serializeConsentCookie([
        { group: 'group-essential', accepted: true },
        { group: 'group-statistics', accepted: false },
      ])
    ).toBe('group-essential.1,group-statistics.0,dismiss');
  });

  test('an empty choice list still yields the dismiss token', () => {
    expect(serializeConsentCookie([])).toBe('dismiss');
  });

  test('round-trips through parseConsentCookie', () => {
    const choices = [
      { group: 'a', accepted: true },
      { group: 'b', accepted: false },
    ];
    expect(parseConsentCookie(serializeConsentCookie(choices))).toEqual(choices);
  });
});

describe('resolvePanelAction', () => {
  const checkboxes = [
    { value: 'group-essential', checked: true, essential: true },
    { value: 'group-statistics', checked: true, essential: false },
  ];

  test('"all" accepts every group regardless of checked state', () => {
    const { choices, checked } = resolvePanelAction('all', checkboxes);
    expect(choices).toEqual([
      { group: 'group-essential', accepted: true },
      { group: 'group-statistics', accepted: true },
    ]);
    expect(checked).toEqual({ 'group-essential': true, 'group-statistics': true });
  });

  test('"min" only accepts essential groups', () => {
    const { choices, checked } = resolvePanelAction('min', checkboxes);
    expect(choices).toEqual([
      { group: 'group-essential', accepted: true },
      { group: 'group-statistics', accepted: false },
    ]);
    expect(checked).toEqual({ 'group-essential': true, 'group-statistics': false });
  });

  test('"save" mirrors each checkbox\'s current checked state', () => {
    const mixed = [
      { value: 'group-essential', checked: true, essential: true },
      { value: 'group-statistics', checked: false, essential: false },
    ];
    const { choices } = resolvePanelAction('save', mixed);
    expect(choices).toEqual([
      { group: 'group-essential', accepted: true },
      { group: 'group-statistics', accepted: false },
    ]);
  });
});

describe('hasUnknownGroups', () => {
  test('true when the panel has a group the stored consent does not', () => {
    const stored = [{ group: 'group-essential', accepted: true }];
    expect(hasUnknownGroups(stored, ['group-essential', 'group-statistics'])).toBe(true);
  });

  test('false when every panel group is already stored', () => {
    const stored = [
      { group: 'group-essential', accepted: true },
      { group: 'group-statistics', accepted: false },
    ];
    expect(hasUnknownGroups(stored, ['group-essential', 'group-statistics'])).toBe(false);
  });
});
