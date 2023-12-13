{{-- order_confirmation.blade.php --}}
{{-- <p>Xin chào,</p>

<p>Cảm ơn bạn đã đặt hàng. Đây là xác nhận đơn hàng của bạn:</p> --}}

{{-- Hiển thị thông tin đơn hàng --}}
{{-- {{ var_dump($orderData) }} --}}

<body>
    <div class="container email-container " style="max-width: 700px; box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;  border-radius: 5px; border: 1px, solid, #ddd; font-family: 'Montserrat', sans-serif; margin: 0 auto;">
        <div  style="display: flex;">
            <div  style="border-bottom:1px solid rgb(217, 217, 217) ; text-align: center; margin: 0 auto; padding: 1em 0;">
                    <img src="https://i.pinimg.com/564x/e8/df/01/e8df017b8459c3fdc951da06ce0be115.jpg" alt=""style="max-width: 300px;">
            </div>
        </div>

         <h4 class="text-center mt-3 "  style="font-weight: 700;text-align: center; margin-top: 1em;">Kính gửi: Quý khách TRAN BICH TRIEU</h4>
         <h5  class="text-center"  style="text-align: center;">Đơn hàng của bạn đã được thanh toán thành công</h5>

         <div class="mail-contents mt-4" style="padding: 0 10px;margin-top: 1.5em;">
            <h4  class="text-center " style=" color: #FE6531;font-weight: 800;text-align: center;">Chi tiết hoá đơn</h4>
            <div class="row mt-4" style="display: flex;margin-top: 1em;">
                <div class="col text-start" style="font-weight: 700;text-align: left; width: 40%;">Mã hoá đơn</div>
                <div class="col text-end" style="text-align: right; width: 60%;">dsahdsds</div>
            </div>
            <div class="row mt-2" style="display: flex;margin-top: 1em;">
                <div class="col text-start"  style="font-weight: 700;text-align: left; width: 40%;">Mã vé</div>
                <div class="col text-end" style="text-align: right; width: 60%;">dsahdsds, sshudsa</div>
            </div>
            <div class="row mt-2" style="display: flex;margin-top: 1em;">
                <div class="col text-start"  style="font-weight: 700;text-align: left; width: 40%;">Khách hàng</div>
                <div class="col text-end" style="text-align: right; width: 60%;">Triệu Trần</div>
            </div>
            <div class="row mt-2" style="display: flex;margin-top: 1em;">
                <div class="col text-start" style="font-weight: 700;text-align: left; width: 40%;">Số điện thoại</div>
                <div class="col text-end" style="text-align: right; width: 60%;">0938932678</div>
            </div>
            <div class="row mt-2" style="display: flex;margin-top: 1em;">
                <div class="col text-start"  style="font-weight: 700;text-align: left; width: 40%;">Tuyến xe</div>
                <div class="col text-end" style="text-align: right; width: 60%;">Bến xe Miền Tây => Bến xe Đà Lạt</div>
            </div>
            <div class="row mt-2" style="display: flex;margin-top: 1em;">
                <div class="col text-start"  style="font-weight: 700;text-align: left; width: 40%;">Số lượng vé</div>
                <div class="col text-end" style="text-align: right; width: 60%;">2</div>
            </div>
            <div class="row mt-2" style="display: flex;margin-top: 1em;">
                <div class="col text-start" style="font-weight: 700;text-align: left; width: 40%;">Ghế</div>
                <div class="col text-end" style="text-align: right; width: 60%;">A1, B1</div>
            </div>
            <div class="row mt-2" style="display: flex;margin-top: 1em;">
                <div class="col text-start"  style="font-weight: 700;text-align: left; width: 40%;">Ngày khởi hành</div>
                <div class="col text-end" style="text-align: right; width: 60%;">20/12/2023</div>
            </div>
            <div class="row mt-2" style="display: flex;margin-top: 1em;">
                <div class="col text-start"  style="font-weight: 700;text-align: left; width: 40%;">Giờ</div>
                <div class="col text-end" style="text-align: right; width: 60%;"> 00:00</div>
            </div>
            <div class="row mt-2" style="display: flex;margin-top: 1em;">
                <div class="col text-start"  style="font-weight: 700;text-align: left; width: 40%;">Điểm đón</div>
                <div class="col text-end"style="text-align: right; width: 60%;">Cổng chính (Quốc Lộ 22, Ấp Đông Lân, Bà Điểm, Hóc Môn, TP Hồ Chí Minh)</div>
            </div>
            
            <div class="row mt-2" style="display: flex;margin-top: 1em;">
                <div class="col text-start"  style="font-weight: 700;text-align: left; width: 40%;">Phương thức thanh toán</div>
                <div class="col text-end" style="text-align: right; width: 60%;">Thanh toán momo</div>
            </div>
            <div class="row mt-3" style="background-color: rgb(218, 218, 218) ;padding: 10px 5px; color: #FE6531; font-weight: 800;margin-top: 1.5em;display: flex;">
                <div class="col text-start " style="font-weight: 700;text-align: left; width: 40%;">Tổng thanh toán</div>
                <div class="col text-end" style="text-align: right; width: 60%;">500,000 VND</div>
            </div>
            <p class="text-center py-3"  style="text-align: center; padding: 15px 0;">Mang mã vé đến văn phòng để đổi vé lên xe trước giời xuất bến ít nhất 60 phút</p>
         </div>
    </div>
</body>