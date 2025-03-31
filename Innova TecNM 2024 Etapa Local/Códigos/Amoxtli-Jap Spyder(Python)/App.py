from flask import Flask, render_template, jsonify

app = Flask(__name__)
alert_status = False

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/about')
def about():
    return render_template('about.html')

@app.route('/alert', methods=['GET'])
def alert():
    global alert_status
    alert_status = True
    return "Alerta de caída recibida.", 200

@app.route('/check_alert', methods=['GET'])
def check_alert():
    global alert_status
    response = jsonify({'alert': alert_status})
    alert_status = False  # Restablecer la alerta después de verificarla
    return response

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=80)