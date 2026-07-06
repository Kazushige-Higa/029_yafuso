(function () {
    function createLightbox() {
        const lb = document.createElement('div');
        lb.className = 'lightbox photobox-lightbox';
        lb.innerHTML = `
      <div class="lightbox-content">
        <div class="lightbox-controls prev-btn" aria-label="Previous">&#10094;</div>
        <div class="lightbox-media"></div>
        <div class="lightbox-controls next-btn" aria-label="Next">&#10095;</div>
        <div class="close-btn" aria-label="Close">&times;</div>
        <div class="image-counter">1/1</div>
      </div>
      <div class="thumb-strip" aria-label="Thumbnails">
        <button class="thumb-scroll left" aria-label="Scroll Left">&#10094;</button>
        <div class="thumbs"></div>
        <button class="thumb-scroll right" aria-label="Scroll Right">&#10095;</button>
      </div>`;
        document.body.appendChild(lb);
        return lb;
    }

    function setupPhotobox(ul) {
        const items = Array.from(ul.querySelectorAll('li')).map(li => {
            const vid = li.querySelector('video');
            if (vid) {
                try {
                    vid.autoplay = false;
                    vid.pause();
                } catch (_) { }
                const source = vid.currentSrc || vid.getAttribute('src') || (vid.querySelector('source') ? vid.querySelector('source').getAttribute('src') : '');
                return {
                    type: 'video',
                    src: source,
                    poster: vid.getAttribute('poster') || '',
                    loop: vid.hasAttribute('loop'),
                    muted: vid.hasAttribute('muted'),
                    alt: vid.getAttribute('alt') || ''
                };
            }
            const img = li.querySelector('img');
            if (img) {
                return {
                    type: 'image',
                    thumb: img.getAttribute('src'),
                    full: img.getAttribute('data-full') || img.getAttribute('src'),
                    alt: img.getAttribute('alt') || ''
                };
            }
            return null;
        }).filter(Boolean);

        const launcherItem = ul.querySelector('li:first-child');
        const launcher = launcherItem ? (launcherItem.querySelector('img,video') || launcherItem) : null;
        const lightbox = createLightbox();
        const mediaWrap = lightbox.querySelector('.lightbox-media');
        const counterEl = lightbox.querySelector('.image-counter');
        const prevBtn = lightbox.querySelector('.prev-btn');
        const nextBtn = lightbox.querySelector('.next-btn');
        const closeBtn = lightbox.querySelector('.close-btn');
        const thumbsWrap = lightbox.querySelector('.thumbs');
        const scrollLeftBtn = lightbox.querySelector('.thumb-scroll.left');
        const scrollRightBtn = lightbox.querySelector('.thumb-scroll.right');

        let index = 0;

        // Thumbnails
        thumbsWrap.innerHTML = '';
        items.forEach((data, i) => {
            const t = document.createElement('div');
            t.className = 'thumb-item' + (i === 0 ? ' active' : '');
            if (data.type === 'video') t.classList.add('video');
            t.dataset.index = String(i);
            const im = document.createElement('img');
            im.src = (data.type === 'video') ? (data.poster || '') : data.thumb;
            im.alt = data.alt || '';
            t.appendChild(im);
            thumbsWrap.appendChild(t);
        });

        function updateUI() {
            const pv = mediaWrap.querySelector('video');
            if (pv) {
                try {
                    pv.pause();
                } catch (_) { }
            }
            mediaWrap.innerHTML = '';
            const item = items[index];
            if (item.type === 'video') {
                const v = document.createElement('video');
                if (item.poster) v.poster = item.poster;
                v.controls = true;
                v.autoplay = true;
                v.muted = true;
                v.playsInline = true;
                if (item.loop) v.loop = true;
                v.className = 'lightbox-video';
                if (item.src) v.src = item.src;
                mediaWrap.appendChild(v);
                try {
                    v.load();
                    v.play();
                } catch (_) { }
            } else {
                const img = new Image();
                img.src = item.full;
                img.alt = item.alt || '';
                img.className = 'lightbox-image';
                mediaWrap.appendChild(img);
            }
            counterEl.textContent = `${index + 1}/${items.length}`;
            lightbox.querySelectorAll('.thumb-item').forEach(el => el.classList.remove('active'));
            const active = lightbox.querySelector(`.thumb-item[data-index="${index}"]`);
            if (active) active.classList.add('active');
        }

        function open(i) {
            index = i;
            updateUI();
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function close() {
            const v = mediaWrap.querySelector('video');
            if (v) {
                try {
                    v.pause();
                } catch (_) { }
            }
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }

        function next() {
            index = (index + 1) % items.length;
            updateUI();
        }

        function prev() {
            index = (index - 1 + items.length) % items.length;
            updateUI();
        }

        if (launcher) launcher.addEventListener('click', e => {
            e.preventDefault();
            open(0);
        });
        nextBtn.addEventListener('click', next);
        prevBtn.addEventListener('click', prev);
        closeBtn.addEventListener('click', close);
        lightbox.addEventListener('click', e => {
            if (e.target === lightbox) close();
        });
        thumbsWrap.addEventListener('click', e => {
            const item = e.target.closest('.thumb-item');
            if (!item) return;
            index = parseInt(item.dataset.index, 10) || 0;
            updateUI();
        });
        const scrollAmount = 180;
        scrollLeftBtn.addEventListener('click', () => {
            thumbsWrap.scrollLeft -= scrollAmount;
        });
        scrollRightBtn.addEventListener('click', () => {
            thumbsWrap.scrollLeft += scrollAmount;
        });

        document.addEventListener('keydown', (e) => {
            if (!lightbox.classList.contains('active')) return;
            if (e.key === 'Escape') close();
            if (e.key === 'ArrowRight') next();
            if (e.key === 'ArrowLeft') prev();
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('ul.photobox, .js-photobox').forEach(setupPhotobox);
    });
})();