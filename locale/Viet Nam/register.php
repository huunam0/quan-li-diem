<?php
$locale['400'] = "Đăng ký";
$locale['401'] = "Kích hoạt tài khoản";
// Registration Errors
$locale['402'] = "Bạn phải hoàn tất Tên đăng nhập, mật khẩu và Email.";
$locale['403'] = "Tên đăng nhập chứa ký tự sai.";
$locale['404'] = "Hai mật khẩu của bạn không khớp.";
$locale['405'] = "Sai mật khẩu, shỉ sử dụng các số và ký tự Anphabe.<br />
Mật khẩu phải có ít nhất 6 ký tự.";
$locale['406'] = "Địa chỉ Email không hợp lệ.";
$locale['407'] = "Xin lỗi, tên đăng nhập ".(isset($_POST['username']) ? $_POST['username'] : "")." đã được sử dụng.";
$locale['408'] = "Xin lỗi, địa chỉ Email ".(isset($_POST['email']) ? $_POST['email'] : "")." đã được sử dụng.";
$locale['409'] = "Một tài khoản chưa kích hoạt đã được đăng ký với địa chỉ Email.";
$locale['410'] = "Mã kiểm tra sai.";
$locale['411'] = "Địa chỉ Email hoặc tên miền Email của bạn thuộc danh sách đen";
// Email Message
$locale['449'] = $settings['sitename']." don thanh vien moi!";
$locale['450'] = "\n\nChao ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Hoan nghenh ban den voi ".$settings['sitename'].".\nDay la thong tin Dang nhap cua ban:\n
Ten dang nhap: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Mat khau     : ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
\nDe hoan ta qua trinh dang ky,
Hay nhap vao Link ben duoi de kich hoat tai khoan cua ban:\n";
// Registration Success/Fail
$locale['451'] = "Đăng ký hoàn tất";
$locale['452'] = "Bạn có thể Đăng nhập ngay bay giờ.";
$locale['453'] = "Ban quản trị sẽ kích hoạt tài khoản của bạn trong thời gian ngắn nhất.";
$locale['454'] = "Quá trình đăng ký gần như hoàn tất, bạn sẽ nhận một email có đầy đủ thông tin đăng nhập.
<br /><br />Hãy đăng nhập Hộp thư của bạn để kiểm tra tài khoản và nhận được link kích hoạt.<br /><br />
Cũng có thể Email của chúng tôi gửi đến bạn bị xem là thư Spam, bạn hãy kiểm tra hộp thư Spam (thư Rác) của bạn.<br /><br />
<strong><font color='red'>Link Email hữu ích</font></strong>:<br />
<a href='http://mail.yahoo.com'>http://mail.yahoo.com</a><br />
<a href='http://gmail.com'>http://gmail.com</a><br />
<a href='http://mail.zing.vn'>http://mail.zing.vn</a><br />";
$locale['455'] = "Tài khoản của bạn đã được kiểm tra";
$locale['456'] = "Quá trình Đăng ký thất bại";
$locale['457'] = "Gửi Email thất bại, hãy liên hệ với <a href='mailto:".$settings['siteemail']."'>Ban Quản trị</a>.";
$locale['458'] = "Những lỗi dẫn đến quá trình đăng ký thất bại:";
$locale['459'] = "Hãy thử lại lần nữa";
// Register Form
$locale['500'] = "<div align='left' style='padding: 10px 20px 10px 20px'>Hãy nhập đầy đủ các thông tin yêu cầu bên dưới.";
$locale['501'] = "Một email xác minh sẽ được gửi đến địa chỉ Email của bạn.<br />";
$locale['502'] = "Những mục đánh dấu <span style='color:#ff0000;'>*</span> bạn phải hoàn thành.<br>
Tên đăng nhâp và mật khẩu phụ thuộc vào kiểu chữ.</div>";
$locale['503'] = " Bạn có thể nhập thêm thông tin của mình trong mục Thông tin cá nhân khi đã đăng nhập.";
$locale['504'] = "Mã xác nhận:";
$locale['505'] = "Nhập mã xác nhận:";
$locale['506'] = "Đăng ký";
$locale['507'] = "Hệ thống đăng ký thành viên đã tạm ngừng.";
$locale['508'] = "Thỏa thuận đăng ký";
$locale['509'] = "J'ai lu et je me mets d'accord sur dessus condition. ";
// Validation Errors
$locale['550'] = "Hãy điền một Tên sử dụng.";
$locale['551'] = "Chưa điền mật khẩu.";
$locale['552'] = "Chưa có địa chỉ E-mail.";
?>
