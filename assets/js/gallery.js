/**
 * Product Image Gallery Controller
 * Supports thumbnail clicking, prev/next navigation, keyboard arrows, and responsive thumbnails
 */
document.addEventListener('DOMContentLoaded', function () {
    const mainImg = document.getElementById('main-product-image');
    const thumbnails = document.querySelectorAll('.gallery-thumb-item');
    const prevBtn = document.getElementById('gallery-prev-btn');
    const nextBtn = document.getElementById('gallery-next-btn');
    const thumbTrack = document.getElementById('gallery-thumb-track');

    if (!mainImg || thumbnails.length === 0) return;

    let currentIndex = 0;

    // Tìm index ảnh active ban đầu
    thumbnails.forEach((thumb, idx) => {
        if (thumb.classList.contains('active')) {
            currentIndex = idx;
        }
    });

    function showImage(index) {
        if (index < 0) index = thumbnails.length - 1;
        if (index >= thumbnails.length) index = 0;
        currentIndex = index;

        const targetThumb = thumbnails[currentIndex];
        const newSrc = targetThumb.getAttribute('data-full-img');

        if (newSrc && mainImg.getAttribute('src') !== newSrc) {
            // Hiệu ứng mờ nhẹ khi chuyển ảnh
            mainImg.style.opacity = '0.35';
            setTimeout(() => {
                mainImg.src = newSrc;
                mainImg.style.opacity = '1';
            }, 120);
        }

        // Cập nhật viền thumbnail đang chọn
        thumbnails.forEach(t => t.classList.remove('active'));
        targetThumb.classList.add('active');

        // Cập nhật số đếm index (1/4, 2/4...)
        const counterEl = document.getElementById('gallery-current-idx');
        if (counterEl) {
            counterEl.textContent = (currentIndex + 1);
        }

        // Cuộn ảnh thu nhỏ vào vùng nhìn thấy
        if (thumbTrack) {
            const thumbLeft = targetThumb.offsetLeft;
            const thumbWidth = targetThumb.offsetWidth;
            const trackWidth = thumbTrack.clientWidth;
            thumbTrack.scrollTo({
                left: thumbLeft - (trackWidth / 2) + (thumbWidth / 2),
                behavior: 'smooth'
            });
        }
    }

    // Sự kiện click thumbnail
    thumbnails.forEach((thumb, idx) => {
        thumb.addEventListener('click', function () {
            showImage(idx);
        });
    });

    // Sự kiện nút chuyển ảnh trái / phải
    if (prevBtn) {
        prevBtn.addEventListener('click', function (e) {
            e.preventDefault();
            showImage(currentIndex - 1);
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function (e) {
            e.preventDefault();
            showImage(currentIndex + 1);
        });
    }

    // Hỗ trợ phím mũi tên trái / phải
    document.addEventListener('keydown', function (e) {
        // Chỉ nhận khi không đang gõ trong form input/textarea
        if (['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) return;

        if (e.key === 'ArrowLeft') {
            showImage(currentIndex - 1);
        } else if (e.key === 'ArrowRight') {
            showImage(currentIndex + 1);
        }
    });
});

