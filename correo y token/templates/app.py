from flask import Flask, render_template, request, redirect, url_for, flash
import psycopg2
from flask_mail import Mail, Message
from itsdangerous import URLSafeTimedSerializer, SignatureExpired
import os

app = Flask(__name__)

# Configuración de Flask-Mail usando Outlook
app.config['MAIL_SERVER'] = 'smtp.hostinger.com'  # El servidor SMTP de Hostinger
app.config['MAIL_PORT'] = 465  # El puerto SSL para SMTP
app.config['MAIL_USE_SSL'] = True  # Usar SSL (ya que es el puerto 465)
app.config['MAIL_USE_TLS'] = False  # No usar TLS porque estamos usando SSL
app.config['MAIL_USERNAME'] = 'support@ecorecetas.com'  # Coloca tu correo profesional
app.config['MAIL_PASSWORD'] = 'EcoSupport@2024'  # Coloca la contraseña de tu correo
app.config['MAIL_DEFAULT_SENDER'] = 'support@ecorecetas.com'  # Correo remitente

mail = Mail(app)

# Generador de tokens
s = URLSafeTimedSerializer('Thisisasecret!')

# Conexión a la base de datos
def get_db_connection():
    conn = psycopg2.connect("dbname=crud_project user=postgres password=1234567")
    return conn

# Ruta de la página de inicio
@app.route('/')
def index():
    return render_template('index.html')

# Ruta para insertar cliente
@app.route('/insert', methods=['GET', 'POST'])
def insert():
    if request.method == 'POST':
        name = request.form['name']
        age = request.form['age']
        gender = request.form['gender']
        password = request.form['password']
        email = request.form['email']
        try:
            conn = get_db_connection()
            cursor = conn.cursor()
            cursor.execute("INSERT INTO clientes (nombre, edad, sexo, password, correo) VALUES (%s, %s, %s, %s, %s)",
                           (name, age, gender, password, email))
            conn.commit()

            # Generar token de confirmación
            token = s.dumps(email, salt='email-confirm')

            # Enviar correo de confirmación
            msg = Message('Confirma tu correo', recipients=[email])
            link = url_for('confirm_email', token=token, _external=True)
            msg.body = f'Por favor confirma tu correo haciendo clic en el siguiente enlace: {link}'
            mail.send(msg)

            cursor.close()
            conn.close()

            flash('Se te ha enviado un correo de confirmación.')
            return redirect(url_for('index'))
        except Exception as e:
            return str(e)
    return render_template('insert.html')

# Ruta para confirmar correo
@app.route('/confirm_email/<token>')
def confirm_email(token):
    try:
        email = s.loads(token, salt='email-confirm', max_age=3600)  # Token válido por 1 hora
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("UPDATE clientes SET confirmado = TRUE WHERE correo = %s", (email,))
        conn.commit()
        cursor.close()
        conn.close()
        return render_template('confirm_email.html')
    except SignatureExpired:
        return '<h1>El enlace de confirmación ha expirado.</h1>'

# Ruta para actualizar cliente
@app.route('/update', methods=['GET', 'POST'])
def update():
    if request.method == 'POST':
        id = request.form['id']
        name = request.form['name']
        age = request.form['age']
        gender = request.form['gender']
        password = request.form['password']
        email = request.form['email']
        try:
            conn = get_db_connection()
            cursor = conn.cursor()
            cursor.execute("UPDATE clientes SET nombre=%s, edad=%s, sexo=%s, password=%s, correo=%s WHERE id=%s",
                           (name, age, gender, password, email, id))
            conn.commit()
            cursor.close()
            conn.close()
            flash('Cliente actualizado correctamente.')
            return redirect(url_for('index'))
        except Exception as e:
            return str(e)
    return render_template('update.html')

# Ruta para eliminar cliente
@app.route('/delete', methods=['GET', 'POST'])
def delete():
    if request.method == 'POST':
        id = request.form['id']
        try:
            conn = get_db_connection()
            cursor = conn.cursor()

            # Recuperar el correo electrónico antes de eliminar el registro
            cursor.execute("SELECT correo FROM clientes WHERE id=%s", (id,))
            email = cursor.fetchone()[0]

            # Eliminar el registro
            cursor.execute("DELETE FROM clientes WHERE id=%s", (id,))
            conn.commit()
            cursor.close()
            conn.close()

            flash('Cliente eliminado correctamente.')
            return redirect(url_for('index'))
        except Exception as e:
            return str(e)
    return render_template('delete.html')

# Ruta para ver clientes
@app.route('/select')
def select():
    try:
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM clientes")
        rows = cursor.fetchall()
        cursor.close()
        conn.close()
        return render_template('select.html', rows=rows)
    except Exception as e:
        return str(e)

# Ruta para restablecer contraseña
@app.route('/reset_password', methods=['GET', 'POST'])
def reset_password():
    if request.method == 'POST':
        email = request.form['email']
        new_password = request.form['new_password']
        try:
            conn = get_db_connection()
            cursor = conn.cursor()
            cursor.execute("UPDATE clientes SET password=%s WHERE correo=%s", (new_password, email))
            conn.commit()
            cursor.close()
            conn.close()
            flash('Contraseña restablecida correctamente.')
            return redirect(url_for('index'))
        except Exception as e:
            return str(e)
    return render_template('reset_password.html')

if __name__ == '__main__':
    app.secret_key = 'some_secret_key'
    app.run(debug=True)
