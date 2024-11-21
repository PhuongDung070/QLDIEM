// ===============================
// DOMContentLoaded Event
// ===============================
document.addEventListener('DOMContentLoaded', function () {

    // ===============================
    // Slidebar Toggle
    // ===============================
    function initSlidebar() {
        const slidebar = document.getElementById('slidebar');
        const menuToggle = document.getElementById('menu-toggle');

        menuToggle.addEventListener('click', function () {
            slidebar.classList.toggle('active');
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth <= 900) {
                slidebar.classList.remove('active'); // Tự động đóng slidebar khi màn hình nhỏ
            }
        });
    }

    // ===============================
    // Profile Dropdown Menu
    // ===============================
    function initProfileDropdown() {
        const profile = document.querySelector('.profile');
        const dropdownMenu = document.getElementById('dropdownMenu');

        profile.addEventListener('click', function (event) {
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
            event.stopPropagation(); // Ngăn sự kiện lan ra ngoài
        });

        // Đóng dropdown menu khi nhấn ra ngoài
        document.addEventListener('click', function (event) {
            if (!profile.contains(event.target)) {
                dropdownMenu.style.display = 'none';
            }
        });
    }

    // ===============================
    // Sidebar Overflow Handling
    // ===============================
    function handleSidebarOverflow() {
        const sidebar = document.getElementById("slidebar");
        if (sidebar.scrollHeight > sidebar.clientHeight) {
            sidebar.style.overflowY = "auto"; // Thêm thanh trượt nếu nội dung quá dài
        } else {
            sidebar.style.overflowY = "hidden"; // Ẩn thanh trượt nếu không cần thiết
        }
    }

    // ===============================
    // Popup Handling
    // ===============================
    function initPopupEvents() {
        const openPopup = document.getElementById("openPopup");
        const closePopup = document.getElementById("closePopup");
        const popupOverlay = document.getElementById("popupOverlay");

        // Hiển thị popup
        openPopup.addEventListener("click", (e) => {
            e.preventDefault();
            popupOverlay.style.display = "flex";
        });

        // Đóng popup
        closePopup.addEventListener("click", () => {
            popupOverlay.style.display = "none";
        });
    }

    // ===============================
    // Change Password Form
    // ===============================
    function initChangePasswordForm() {
        document.getElementById("changePasswordForm").addEventListener("submit", function(event) {
            event.preventDefault(); 

            const oldPassword = document.getElementById("oldPassword").value;
            const newPassword = document.getElementById("newPassword").value;
            const confirmPassword = document.getElementById("confirmPassword").value;

            if (newPassword !== confirmPassword) {
                alert("Mật khẩu xác nhận không trùng khớp.");
                return;
            }

            // Tạo đối tượng FormData để gửi dữ liệu qua AJAX
            const formData = new FormData();
            formData.append("oldPassword", oldPassword);
            formData.append("newPassword", newPassword);
            formData.append("confirmPassword", confirmPassword);

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "change_password.php", true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert(xhr.responseText); 
                    if (xhr.responseText === "Đổi mật khẩu thành công.") {
                        document.getElementById("popupOverlay").style.display = "none";
                    }
                } else {
                    alert("Có lỗi xảy ra. Vui lòng thử lại.");
                }
            };
            xhr.send(formData);
        });
    }

    // ===============================
    // Tab Handling
    // ===============================
window.openTab = function(evt, tabId) {
        // Ẩn tất cả nội dung tab
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => content.style.display = 'none');

        // Xóa class 'active' trên tất cả nút
        const buttons = document.querySelectorAll('.tab-button');
        buttons.forEach(button => button.classList.remove('active'));

        // Hiển thị tab được chọn
        const activeTab = document.getElementById(tabId);
        if (activeTab) {
            activeTab.style.display = 'block';
        }

        // Thêm class 'active' vào nút được chọn
        evt.currentTarget.classList.add('active');
    }

    // Hiển thị tab đầu tiên mặc định
    function showDefaultTab() {
        const firstTabButton = document.querySelector('.tab-button');
        if (firstTabButton) {
            firstTabButton.click();
        }
    }

    // ===============================
    // Delete Popup Handling
    // ===============================
    // Open the delete popup when the "Xóa" button is clicked
	const openPopupDelete = document.getElementById('openPopupDelete');
	if (openPopupDelete) {
		openPopupDelete.addEventListener('click', function() {
			document.getElementById('popupOverlayDelete').style.display = 'block';
		});
	} else {
		console.log('openPopupDelete element not found');
	}


    // Close the delete popup when the "Hủy" button is clicked
	const closePopupDelete = document.getElementById('closePopupDelete');
	if (closePopupDelete) {
		closePopupDelete.addEventListener('click', function() {			
		document.getElementById('popupOverlayDelete').style.display = 'none';
			});
	} else {
		console.log('closePopupDelete element not found');
	}


    // Function to submit the delete form based on the selected option
     window.submitDeleteForm = function(type) {
       document.getElementById('deleteType').value = type;
        let selectedStudents = [];
        document.querySelectorAll('input[name="selected_students[]"]:checked').forEach(function(checkbox) {
            selectedStudents.push(checkbox.value);
        });

        // If there are selected students, set them in the hidden input
        if (selectedStudents.length > 0) {
            document.getElementById('selectedStudents').value = selectedStudents.join(',');
            document.getElementById('deleteForm').submit();
        } else {
            alert("Vui lòng chọn sinh viên cần xóa.");
        }
    }

    // Khởi tạo các chức năng
    initSlidebar();
    initProfileDropdown();
    handleSidebarOverflow();
    initPopupEvents();
    initChangePasswordForm();

    // Gọi hàm hiển thị tab mặc định
    showDefaultTab();
	
	function showDetails(maLHP) {
    fetch(`get_details.php?MaLHP=${maLHP}`)
        .then(response => response.json())
        .then(data => {
            const detailTable = document.getElementById('detailTable').querySelector('tbody');
            detailTable.innerHTML = `
                <tr>
                    <td>${data.DiemCC}</td>
                    <td>${data.DiemKT1}</td>
                    <td>${data.DiemKT2}</td>
                    <td>${data.DiemTL}</td>
                    <td>${data.DiemKTHP}</td>
                </tr>`;
            document.getElementById('detailPopup').style.display = 'block';
        });
}
	
	

});


    
