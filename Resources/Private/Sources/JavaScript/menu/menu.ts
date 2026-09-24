import resizeManger from '../modules/resize-manager'
import {Transition} from './animation'

// desktop and mobile menus are separate widgets and keep separate state
let activeDesktopSubmenu = '';
let activeMobileSubmenu = '';


function mobileSideNavigation(entry: { target: Element }): void {
    const mobileSidebar = document.getElementById('mobile-sidebar');
    if (!mobileSidebar) {
        return;
    }
    if (window.innerWidth <= 1024) {
        const open = !mobileSidebar.classList.contains('hidden');
        if (open) {
            document.querySelector('body')!.style.setProperty('overflow', 'hidden');
        }
    } else {
        document.querySelector('body')!.style.removeProperty('overflow');
    }
}

document.addEventListener(
    "DOMContentLoaded", function () {
        const mainMenu = document.getElementById('main-menu');
        if (mainMenu) {
            mainMenu.addEventListener(
                'click', function (event) {
                    // the click can land on a child (span/svg) of the button
                    const target = (event.target as Element).closest<HTMLElement>('[data-tid]');
                    if (target && target.dataset.tid) {
                        if (target.dataset.tid !== activeDesktopSubmenu) {
                            activeDesktopSubmenu = target.dataset.tid;
                            event.preventDefault();
                        } else {
                            activeDesktopSubmenu = ''
                            event.preventDefault();
                        }
                        mainMenu.querySelectorAll<HTMLElement>('button.group\\/navbutton').forEach(function (el) {
                            if (activeDesktopSubmenu !== '' && el.dataset.tid === activeDesktopSubmenu) {
                                el.setAttribute('aria-expanded', 'true');
                            } else {
                                el.setAttribute('aria-expanded', 'false');
                            }
                        })
                    }
                }
            );
        }

        const mobileMenuToggler = document.getElementById('mobile-main-menu-toggle');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        if (mobileMenuToggler && mobileSidebar) {
            mobileMenuToggler.addEventListener(
                'click', function (event) {
                    const open = !mobileSidebar.classList.contains('hidden');
                    if (open) {
                        mobileMenuToggler.setAttribute('aria-expanded', 'false');
                        mobileSidebar.classList.add('hidden');
                        document.querySelector('body')!.style.removeProperty('overflow');
                    } else {
                        mobileMenuToggler.setAttribute('aria-expanded', 'true');
                        mobileSidebar.classList.remove('hidden');
                        document.querySelector('body')!.style.setProperty('overflow', 'hidden');
                    }
                    event.preventDefault();
                }
            );
        }
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) {
            const animations = new Map<string, Transition>()
            mobileMenu.querySelectorAll<HTMLElement>('.m-submenu').forEach(function (el) {
                if (el.dataset.sid) {
                    animations.set(el.dataset.sid, new Transition(
                        el,
                        "mobile-dropdown",
                        "overflow-hidden grid grid-rows-[0fr]",
                        "transition-[grid-template-rows] duration-400 ease-in",
                        "overflow-hidden grid grid-rows-[1fr]",
                        "overflow-hidden grid grid-rows-[1fr]",
                        "transition-[grid-template-rows] duration-400 ease-out",
                        "overflow-hidden grid grid-rows-[0fr]")
                    )
                }
            })
            mobileMenu.addEventListener(
                'click', function (event) {
                    const target = (event.target as Element).closest<HTMLElement>('[data-tid]');
                    if (target && target.dataset.tid) {
                        if (target.dataset.tid !== activeMobileSubmenu) {
                            activeMobileSubmenu = target.dataset.tid;
                            event.preventDefault();
                        } else {
                            activeMobileSubmenu = ''
                            event.preventDefault();
                        }
                        mobileMenu.querySelectorAll<HTMLElement>('button.group\\/mnavbutton').forEach(function (el) {
                            if (activeMobileSubmenu !== '' && el.dataset.tid === activeMobileSubmenu) {
                                if (animations.has(el.dataset.tid)) {
                                    el.setAttribute('aria-expanded', 'true');
                                    animations.get(el.dataset.tid)!.enter();
                                }
                            } else {
                                if (animations.has(el.dataset.tid!)) {
                                    el.setAttribute('aria-expanded', 'false');
                                    if (animations.get(el.dataset.tid!)!.el.style.display !== 'none') {
                                        animations.get(el.dataset.tid!)!.leave();
                                    }
                                }
                            }
                        })
                    }
                }
            );
        }


        const body = document.querySelector('body');
        if (body) {
            mobileSideNavigation({target: body})
            resizeManger.registerElement(body, mobileSideNavigation)
        }
    }
);
