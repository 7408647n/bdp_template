/**
 * Small pure-logic helper for the group directory list/filter UI.
 * Not present as a separate file in the TYPO3 source (the original list.js
 * only wired up Masonry), but the WordPress archive template exposes a
 * text filter over the rendered items, so the logic lives here, isolated
 * and unit-testable with bun test.
 */
export interface FilterableGroup {
  name: string;
  city: string;
}

/**
 * Returns the indexes of `groups` whose name or city contain `query`
 * (case-insensitive, diacritic-insensitive is out of scope here).
 */
export function filterGroups<T extends FilterableGroup>(groups: T[], query: string): T[] {
  const needle = query.trim().toLowerCase();
  if (!needle) return groups;

  return groups.filter((group) => {
    return (
      group.name.toLowerCase().includes(needle) || group.city.toLowerCase().includes(needle)
    );
  });
}
