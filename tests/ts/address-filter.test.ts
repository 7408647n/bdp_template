import { describe, expect, test } from 'bun:test';
import { filterGroups } from '../../assets/src/ts/address/filter';

const groups = [
  { name: 'Stamm Falke', city: 'Kassel' },
  { name: 'Stamm Adler', city: 'Marburg' },
  { name: 'Landesverband Hessen', city: 'Kassel' },
];

describe('filterGroups', () => {
  test('returns all groups for an empty query', () => {
    expect(filterGroups(groups, '')).toEqual(groups);
    expect(filterGroups(groups, '   ')).toEqual(groups);
  });

  test('matches by name, case-insensitively', () => {
    expect(filterGroups(groups, 'falke')).toEqual([groups[0]]);
  });

  test('matches by city', () => {
    expect(filterGroups(groups, 'kassel')).toEqual([groups[0], groups[2]]);
  });

  test('returns an empty array when nothing matches', () => {
    expect(filterGroups(groups, 'nowhere')).toEqual([]);
  });
});
