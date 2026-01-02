<h2>Đăng tin bán đồ cũ</h2>

<form action="/products/store" method="POST">

    <div>
        <label>Tên sản phẩm</label>
        <input type="text" name="name" required>
    </div>

    <div>
        <label>Giá (VNĐ)</label>
        <input type="number" name="price" required>
    </div>

    <div>
        <label>Mô tả</label>
        <textarea name="description" rows="4"></textarea>
    </div>

    <div>
        <label>Danh mục</label>
        <select name="category_id">
            <option value="1">Sách</option>
            <option value="2">Đồ điện tử</option>
            <option value="3">Đồ học tập</option>
            <option value="4">Khác</option>
        </select>
    </div>

    <button type="submit">Đăng tin</button>
</form>
