import sys
import requests
from PyQt5.QtWidgets import QApplication, QMainWindow, QWidget, QLabel, QLineEdit, QPushButton, QMessageBox, QTabWidget, QVBoxLayout
from datetime import datetime
import re

class LoginWindow(QMainWindow):
    def __init__(self):
        super().__init__()
        self.setWindowTitle("로그인 및 회원가입")
        self.setGeometry(1100, 300, 400, 250)

        central_widget = QWidget()
        self.setCentralWidget(central_widget)

        self.tabs = QTabWidget()
        self.login_tab = QWidget()
        self.register_tab = QWidget()

        self.tabs.addTab(self.login_tab, "로그인")
        self.tabs.addTab(self.register_tab, "회원가입")

        self.init_login_tab()
        self.init_register_tab()

        layout = QVBoxLayout()
        layout.addWidget(self.tabs)
        central_widget.setLayout(layout)

    def init_login_tab(self):
        layout = QVBoxLayout()

        self.username_label = QLabel("아이디:")
        self.username_entry = QLineEdit()
        layout.addWidget(self.username_label)
        layout.addWidget(self.username_entry)

        self.password_label = QLabel("비밀번호:")
        self.password_entry = QLineEdit()
        self.password_entry.setEchoMode(QLineEdit.Password)
        layout.addWidget(self.password_label)
        layout.addWidget(self.password_entry)

        self.login_button = QPushButton("로그인")
        self.login_button.clicked.connect(self.login)
        layout.addWidget(self.login_button)

        self.login_tab.setLayout(layout)

    def init_register_tab(self):
        layout = QVBoxLayout()

        self.register_username_label = QLabel("아이디:")
        self.register_username_entry = QLineEdit()
        layout.addWidget(self.register_username_label)
        layout.addWidget(self.register_username_entry)

        self.register_password_label = QLabel("비밀번호:")
        self.register_password_entry = QLineEdit()
        self.register_password_entry.setEchoMode(QLineEdit.Password)
        layout.addWidget(self.register_password_label)
        layout.addWidget(self.register_password_entry)

        self.register_code_label = QLabel("코드:")
        self.register_code_entry = QLineEdit()
        layout.addWidget(self.register_code_label)
        layout.addWidget(self.register_code_entry)

        self.register_button = QPushButton("회원가입")
        self.register_button.clicked.connect(self.register)
        layout.addWidget(self.register_button)

        self.register_tab.setLayout(layout)

    def login(self):
        username = self.username_entry.text().strip()
        password = self.password_entry.text().strip()

        url = 'http://localhost/License_login.php'
        data = {'username': username, 'password': password, 'submit': 'login'}
        response = requests.post(url, data=data)

        if response.status_code == 200:
            result = response.text.strip()
            if result.startswith('로그인 성공'):
                # 만료일 가져오기
                expired_date = self.get_expired_date(username)
                if expired_date:
                    days_left = (expired_date - datetime.now().date()).days
                    if days_left > 0:
                        QMessageBox.information(self, "로그인 성공", f"{days_left}일 남았습니다.")

                        self.close()
                        self.new_window = NewWindow(days_left)
                        self.new_window.show()
                    else:
                        QMessageBox.critical(self, "로그인 실패", "만료된 계정입니다.")
                else:
                    QMessageBox.critical(self, "로그인 실패", "만료일 정보를 가져올 수 없습니다.")
            else:
                QMessageBox.critical(self, "로그인 실패", result)
        else:
            QMessageBox.critical(self, "에러", "서버와의 연결에 문제가 있습니다.")

    def get_expired_date(self, username):

        url = 'http://localhost/License_user.php'
        response = requests.get(url)

        if response.status_code == 200:
            html = response.text
            user_data = html.split('<br>')

            for entry in user_data:
                if entry.strip():
                    match = re.match(r'Username:\s*(.*?)\s*-\s*Expired Date:\s*(.*?)$', entry.strip())
                    if match and match[1] == username:
                        try:
                            expired_date = datetime.strptime(match[2], '%Y-%m-%d').date()
                            return expired_date
                        except ValueError:
                            return None
        return None

    def register(self):
        username = self.register_username_entry.text().strip()
        password = self.register_password_entry.text().strip()
        code = self.register_code_entry.text().strip()

        if not (username and password and code):
            QMessageBox.warning(self, "경고", "모든 필드를 입력하세요.")
            return

        data = {'username': username, 'password': password, 'code': code}
        response = requests.post('http://localhost/License_register_process.php', data=data)
        result = response.text

        QMessageBox.information(self, "알림", result)

class NewWindow(QWidget):
    def __init__(self, days_left):
        super().__init__()
        self.setWindowTitle("Box")
        self.setGeometry(1100, 300, 300, 200)

        layout = QVBoxLayout()
        self.label = QLabel(f"               로그인 성공 남은 기간: {days_left}일")
        layout.addWidget(self.label)
        
        self.setLayout(layout)

if __name__ == "__main__":
    app = QApplication(sys.argv)
    login_window = LoginWindow()
    login_window.show()
    sys.exit(app.exec_())
