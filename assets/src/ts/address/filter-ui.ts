import { filterGroups, type FilterableGroup } from './filter';

/**
 * Wires the #bdp-group-filter text input (archive-bdp_group.php) to
 * show/hide `.ce-address__list__items__item` cards using the pure
 * filterGroups() logic.
 */
interface DomGroup extends FilterableGroup {
  element: HTMLElement;
}

document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('bdp-group-filter') as HTMLInputElement | null;
  const items = Array.from(
    document.querySelectorAll<HTMLElement>('.ce-address__list__items__item')
  );
  if (!input || items.length === 0) return;

  const groups: DomGroup[] = items.map((element) => ({
    element,
    name: element.dataset.groupName ?? '',
    city: element.dataset.groupCity ?? '',
  }));

  input.addEventListener('input', () => {
    const matches = new Set(filterGroups(groups, input.value).map((g) => g.element));
    groups.forEach((group) => {
      group.element.hidden = !matches.has(group.element);
    });
  });
});
