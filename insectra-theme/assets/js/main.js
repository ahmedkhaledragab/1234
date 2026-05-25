/**
 * Insectra theme - main.js
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {

        // Sticky header shadow on scroll
        var header = document.querySelector('.ins-header');
        if (header) {
            var onScroll = function () {
                header.classList.toggle('is-scrolled', window.scrollY > 8);
            };
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        }

        // Mobile menu toggle
        var toggle = document.querySelector('.ins-menu-toggle');
        var drawer = document.querySelector('.ins-mobile-menu');
        if (toggle && drawer) {
            toggle.addEventListener('click', function () {
                var open = toggle.classList.toggle('is-open');
                if (open) {
                    drawer.removeAttribute('hidden');
                    drawer.style.display = 'block';
                } else {
                    drawer.style.display = 'none';
                    drawer.setAttribute('hidden', '');
                }
            });
        }

        // Counter animation
        var counters = document.querySelectorAll('.ins-counter .num');
        if (counters.length && 'IntersectionObserver' in window) {
            var animateCount = function (el) {
                var target = parseInt(el.dataset.target || '0', 10);
                var duration = 1600;
                var start = performance.now();
                var step = function (now) {
                    var p = Math.min(1, (now - start) / duration);
                    var eased = 1 - Math.pow(1 - p, 3);
                    el.textContent = Math.floor(target * eased).toLocaleString();
                    if (p < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            };
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        animateCount(entry.target);
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.4 });
            counters.forEach(function (c) { io.observe(c); });
        }

        // Smooth scroll for in-page anchors
        document.querySelectorAll('a[href^="#"]').forEach(function (a) {
            var href = a.getAttribute('href');
            if (!href || href === '#' || href.length < 2) return;
            a.addEventListener('click', function (e) {
                var target = document.querySelector(href);
                if (!target) return;
                e.preventDefault();
                var top = target.getBoundingClientRect().top + window.scrollY - 80;
                window.scrollTo({ top: top, behavior: 'smooth' });
            });
        });

        // Show "thanks" notice if redirected from contact form
        if (location.search.indexOf('sent=1') !== -1) {
            var n = document.createElement('div');
            n.className = 'ins-toast';
            n.style.cssText = 'position:fixed;bottom:20px;right:20px;background:#1FAE52;color:#fff;padding:14px 18px;border-radius:12px;box-shadow:0 14px 30px rgba(31,174,82,.4);z-index:9999;font-weight:600';
            n.textContent = (window.INSECTRA && window.INSECTRA.rtl) ? 'تم استلام طلبك. سنتواصل معك قريباً.' : 'Thanks! We received your request.';
            document.body.appendChild(n);
            setTimeout(function () { n.style.opacity = '0'; n.style.transition = 'opacity .4s'; }, 3500);
            setTimeout(function () { n.remove(); }, 4200);
        }
    });
})();
