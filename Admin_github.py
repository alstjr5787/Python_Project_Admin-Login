from PyQt5.QtWidgets import QApplication, QWidget, QLabel, QLineEdit, QPushButton, QVBoxLayout, QFormLayout, QMessageBox, QComboBox
from PyQt5.QtGui import QClipboard
import sys
import random
import string
import requests

class IssueLicenseApp(QWidget):
    def __init__(self):
        super().__init__()
        self.setWindowTitle("Code Maker")
        self.init_ui()
        self.generate_new_code()  

    def init_ui(self):
        self.code_label = QLabel("코드:")
        self.code_entry = QLineEdit()
        self.code_entry.setReadOnly(True)
        self.generate_button = QPushButton("새로고침")
        self.generate_button.clicked.connect(self.generate_new_code)

        self.duration_label = QLabel("기간:")
        self.duration_combo = QComboBox()
        self.duration_combo.addItem("7일")
        self.duration_combo.addItem("15일")
        self.duration_combo.addItem("30일")

        self.issue_button = QPushButton("코드 발급")
        self.issue_button.clicked.connect(self.issue_license)

        form_layout = QFormLayout()
        form_layout.addRow(self.code_label, self.code_entry)
        form_layout.addRow(self.generate_button)
        form_layout.addRow(self.duration_label, self.duration_combo)
        form_layout.addRow(self.issue_button)

        self.result_label = QLabel()
        self.result_label.setWordWrap(True)

        vbox = QVBoxLayout()
        vbox.addLayout(form_layout)
        vbox.addWidget(self.result_label)

        self.setLayout(vbox)
        self.show()

    def generate_new_code(self):
        code_length = 16
        characters = string.ascii_letters + string.digits
        new_code = ''.join(random.choice(characters) for _ in range(code_length))
        self.code_entry.setText(new_code)

    def issue_license(self):
        code = self.code_entry.text()
        duration = self.duration_combo.currentText().split('일')[0]

        # HTTP POST 요청 보내기
        url = 'http://localhost/issue_license.php'
        data = {
            'code': code,
            'duration': duration,
            'submit': '코드 발급'
        }

        response = requests.post(url, data=data)

        if response.status_code == 200:
            result = response.text.strip()

            # 코드 클립보드에 복사하기
            clipboard = QApplication.clipboard()
            clipboard.setText(code)

            QMessageBox.information(self, "알림", "발급완료 코드가 복사되었습니다.")
        else:
            QMessageBox.critical(self, "에러", "서버와의 연결에 문제가 있습니다.")

if __name__ == '__main__':
    app = QApplication(sys.argv)
    ex = IssueLicenseApp()
    sys.exit(app.exec_())
