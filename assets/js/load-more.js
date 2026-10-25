/**
 * Load More Products Module
 * Handles AJAX product pagination using Fetch API
 */
document.addEventListener('DOMContentLoaded', function () {
    const loadMoreBtn = document.getElementById('btn-load-more');
    if (!loadMoreBtn) return;

    const productList = document.querySelector('.product-list');
    const loadMoreContainer = document.getElementById('load-more-container');
    const loadingStatus = document.getElementById('load-more-loading');
    const errorNotice = document.getElementById('load-more-error');

    let currentOffset = parseInt(loadMoreBtn.getAttribute('data-offset'), 10) || 8;
    const limit = parseInt(loadMoreBtn.getAttribute('data-limit'), 10) || 8;
    const hang = loadMoreBtn.getAttribute('data-hang') || '';
    const keyword = loadMoreBtn.getAttribute('data-keyword') || '';
    const mucgia = loadMoreBtn.getAttribute('data-mucgia') || '';
    const sapxep = loadMoreBtn.getAttribute('data-sapxep') || '';
    const endpoint = loadMoreBtn.getAttribute('data-endpoint') || 'xu-ly/xu-ly-load-more.php';

    // Tập hợp ID sản phẩm đã có trên DOM để ngăn trùng lặp
    const existingIds = new Set();
    document.querySelectorAll('.product[data-product-id]').forEach(el => {
        const id = el.getAttribute('data-product-id');
        if (id) existingIds.add(id);
    });

    loadMoreBtn.addEventListener('click', function () {
        // Hiển thị trạng thái đang tải
        loadMoreBtn.style.display = 'none';
        if (loadingStatus) loadingStatus.style.display = 'block';
        if (errorNotice) errorNotice.style.display = 'none';

        const params = new URLSearchParams({
            offset: currentOffset,
            limit: limit,
            hang: hang,
            keyword: keyword,
            mucgia: mucgia,
            sapxep: sapxep
        });

        fetch(endpoint + '?' + params.toString(), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Lỗi máy chủ HTTP: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (loadingStatus) loadingStatus.style.display = 'none';

            if (!data.success) {
                throw new Error(data.message || 'Không thể tải thêm sản phẩm');
            }

            // Chèn sản phẩm mới nếu có
            if (data.html && productList) {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;

                const newCards = tempDiv.querySelectorAll('.product');
                newCards.forEach(card => {
                    const cardId = card.getAttribute('data-product-id');
                    // Ngăn chặn trùng lặp sản phẩm
                    if (!cardId || !existingIds.has(cardId)) {
                        if (cardId) existingIds.add(cardId);
                        productList.appendChild(card);
                    }
                });
            }

            currentOffset = data.nextOffset;
            loadMoreBtn.setAttribute('data-offset', currentOffset);

            // Kiểm tra xem còn sản phẩm không
            if (data.hasMore && data.remaining > 0) {
                loadMoreBtn.style.display = 'inline-block';
                loadMoreBtn.textContent = 'Xem thêm sản phẩm (còn ' + data.remaining + ')';
            } else {
                // Ẩn nút nếu hết dữ liệu
                if (loadMoreContainer) {
                    loadMoreContainer.innerHTML = '<p class="load-more-end-text">Đã hiển thị tất cả ' + data.total + ' sản phẩm</p>';
                } else {
                    loadMoreBtn.style.display = 'none';
                }
            }
        })
        .catch(err => {
            if (loadingStatus) loadingStatus.style.display = 'none';
            if (errorNotice) {
                errorNotice.textContent = 'Không thể tải thêm: ' + err.message + '. Vui lòng bấm thử lại.';
                errorNotice.style.display = 'block';
            }
            loadMoreBtn.style.display = 'inline-block';
            loadMoreBtn.textContent = 'Thử tải lại';
        });
    });
});

