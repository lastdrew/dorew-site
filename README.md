# DorewSite
Xây dựng website với twig

> **Cập nhật**
* Phiên bản dựng Wap4 dựa trên DorewSite 2.0
  * **2024-01-01** v0.2.0-RC2


> **Tính năng**
- Được xây dựng lại với mô hình MVC và NoSQL
- Cho phép các request uri được xử lý tương đương như Wap4.co
- Cho phép các tập tin css/js, hình ảnh, video được tải lên, chúng được hiển thị đúng với định dạng.
- Một số Function/Filter được xây dựng từ DorewSite v1.0.1 được giữ lại.
- Xóa bỏ hệ thống quản trị tệp /cms

> **Yêu cầu hệ thống**
- Apache
- PHP, version 8.0+
- ext-ctype, ext-iconv, ext-json, ext-mbstring, ext-intl, ext-tokenizer

> **Hướng dẫn cài dặt**
- Upload các file lên server
- Xóa thư mục /database nếu muốn làm sạch cơ sở dữ liệu trước khi sử dụng
- Trỏ domain về thư mục chứa *index.php*