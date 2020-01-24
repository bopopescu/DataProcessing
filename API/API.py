from flask import Flask,request,jsonify
from flask_mysqldb import MySQL
import dicttoxml


app = Flask(__name__)
# configure the conexion to MYSQL
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = ''
app.config['MYSQL_DB'] = 'employees'
app.config['MYSQL_CURSORCLASS'] = 'DictCursor'

mysql = MySQL(app)
#the index of the app
@app.route('/', methods=['GET','POST'])
def index():
    return "<h1>Company employees details</h1>"
#create the app route for deleting from the database
@app.route('/DeleteEmployee/<string:last_name>',methods = ['DELETE'])
def deleteEmployee(last_name):
    cur = mysql.connection.cursor()
    cur.execute("DELETE FROM employees WHERE last_name = %s",last_name)
    cur.close()
#create the app route for adding in database
@app.route('/AddEmployee/<string:last_name>,<string:first_name>',methods = ['PUT'])
def addEmployee(first_name,last_name):
    cur = mysql.connection.cursor()
    cur.execute("INSERT INTO employees (first_name,last_name) VALUES (%s,%s)",first_name,last_name)
    cur.close()
#create the app route for update in the database
@app.route('/UpdateLastName/<string:last_name>,<string:first_name>',methods = ['PUT'])
def updateLastName(first_name,last_name):
    cur = mysql.connection.cursor()
    cur.execute("UPDATE employees SET last_name = '%s' WHERE first_name = '%s' ",first_name,last_name)
    cur.close()

#create the app route for returning Info from the database in JSON
@app.route('/JSONdepartmentEmployees', methods=['GET'])
def deptEmployeesJSON():
    #connect to the database
    cur = mysql.connection.cursor()
    #the querry
    cur.execute("SELECT dept_name,first_name,last_name FROM dept_emp JOIN departments ON departments.dept_no=dept_emp.dept_no JOIN employees ON employees.emp_no = dept_emp.emp_no")
    #retrive info and put in a variable
    information = cur.fetchall()
    #create the json object
    sentInformation = jsonify(information)
    #close the conexion 
    cur.close()
    #send the JSON object
    return sentInformation
#create the app route for returning Info from the database in JSON
@app.route('/JSONdepartmentManager', methods=['GET'])
def departmentManagerJSON():
    cur = mysql.connection.cursor()
    cur.execute("SELECT dept_name, first_name, last_name FROM dept_manager JOIN departments ON  departments.dept_no=dept_manager.dept_no JOIN employees ON employees.emp_no = dept_manager.emp_no")
    information = cur.fetchall()
    sentInformation = jsonify(information)
    cur.close()
    return sentInformation
#create the app route for returning Info from the database in JSON
@app.route('/JSONdepartmentsSalary',methods=['GET'])
def departmentsSalaryJSON():
    cur = mysql.connection.cursor()
    cur.execute("SELECT dept_name, salary FROM salaries JOIN departments on departments.dept_no = salaries.dept_no")
    information = cur.fetchall()
    sentInformation = jsonify(information)
    cur.close()
    return sentInformation
#create the app route for returning Info from the database in JSON
@app.route('/JSONhobbiesInfo',methods=['GET'])
def hobbiesInfoJSON():
    cur = mysql.connection.cursor()
    cur.execute("SELECT HobbieName, MoneySpent FROM hobbies")
    information = cur.fetchall()
    sentInformation = jsonify(information)
    cur.close()
    return sentInformation
#create the app route for returning Info from the database in XML
@app.route('/XMLdepartmentEmployees', methods=['GET'])
def deptEmployees():
    #connect to the database
    cur = mysql.connection.cursor()
    #execute the querry
    cur.execute("SELECT dept_name,first_name,last_name FROM dept_emp JOIN departments ON departments.dept_no=dept_emp.dept_no JOIN employees ON employees.emp_no = dept_emp.emp_no")
    #save the information from the database in a variable
    information = cur.fetchall()
    #save the info in an XML object 
    sentInformation = dicttoxml.dicttoxml(information)
    #close the conexion
    cur.close()
    #sent the information
    return sentInformation
#create the app route for returning Info from the database in XML
@app.route('/XMLdepartmentManager', methods=['GET'])
def departmentManager():
    cur = mysql.connection.cursor()
    cur.execute("SELECT dept_name, first_name, last_name FROM dept_manager JOIN departments ON  departments.dept_no=dept_manager.dept_no JOIN employees ON employees.emp_no = dept_manager.emp_no")
    information = cur.fetchall()
    sentInformation = dicttoxml.dicttoxml(information)
    cur.close()
    return sentInformation
#create the app route for returning Info from the database in XML
@app.route('/XMLdepartmentsSalary',methods=['GET'])
def departmentsSalary():
    cur = mysql.connection.cursor()
    cur.execute("SELECT dept_name, salary FROM salaries JOIN departments on departments.dept_no = salaries.dept_no")
    information = cur.fetchall()
    sentInformation = dicttoxml.dicttoxml(information)
    cur.close()
    return sentInformation
#create the app route for returning Info from the database in XML
@app.route('/XMLhobbiesInfo',methods=['GET'])
def hobbiesInfo():
    cur = mysql.connection.cursor()
    cur.execute("SELECT HobbieName, MoneySpent FROM hobbies")
    information = cur.fetchall()
    sentInformation = dicttoxml.dicttoxml(information)
    cur.close()
    return sentInformation




if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
