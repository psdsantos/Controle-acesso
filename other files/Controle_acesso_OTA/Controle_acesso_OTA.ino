// OTA update
#include <WiFi.h>
#include <WiFiClient.h>
#include <WebServer.h>
#include <ESPmDNS.h>
#include <Update.h>

// Comunicação com sistema Web
#include <HTTPClient.h>
#include <Wire.h>
#include <time.h>
#include <Arduino_JSON.h>

const char* host = "esp32";
const char* ssid = "NOME DO WIFI";
const char* password = "SENHA DO WIFI";

String httpRequestData = "";
int httpResponseCode = 0;

// OTA update
// Cria um servidor para receber os dados
// Veja mais em: https://lastminuteengineers.com/esp32-ota-web-updater-arduino-ide/
WebServer server(80);

const char* loginIndex =
 "<form name='loginForm'>"
    "<table width='20%' bgcolor='A09F9F' align='center'>"
        "<tr>"
            "<td colspan=2>"
                "<center><font size=4><b>ESP32 Login Page</b></font></center>"
                "<br>"
            "</td>"
            "<br>"
            "<br>"
        "</tr>"
        "<td>Username:</td>"
        "<td><input type='text' size=25 name='userid'><br></td>"
        "</tr>"
        "<br>"
        "<br>"
        "<tr>"
            "<td>Password:</td>"
            "<td><input type='Password' size=25 name='pwd'><br></td>"
            "<br>"
            "<br>"
        "</tr>"
        "<tr>"
            "<td><input type='submit' onclick='check(this.form)' value='Login'></td>"
        "</tr>"
    "</table>"
"</form>"
"<script>"
    "function check(form)"
    "{"
    "if(form.userid.value=='admin' && form.pwd.value=='admin123')"
    "{"
    "window.open('/serverIndex')"
    "}"
    "else"
    "{"
    " alert('Error Password or Username')/*displays error message*/"
    "}"
    "}"
"</script>";

const char* serverIndex =
"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>"
"<form method='POST' action='#' enctype='multipart/form-data' id='upload_form'>"
   "<input type='file' name='update'>"
        "<input type='submit' value='Update'>"
    "</form>"
 "<div id='prg'>progress: 0%</div>"
 "<script>"
  "$('form').submit(function(e){"
  "e.preventDefault();"
  "var form = $('#upload_form')[0];"
  "var data = new FormData(form);"
  " $.ajax({"
  "url: '/update',"
  "type: 'POST',"
  "data: data,"
  "contentType: false,"
  "processData:false,"
  "xhr: function() {"
  "var xhr = new window.XMLHttpRequest();"
  "xhr.upload.addEventListener('progress', function(evt) {"
  "if (evt.lengthComputable) {"
  "var per = evt.loaded / evt.total;"
  "$('#prg').html('progress: ' + Math.round(per*100) + '%');"
  "}"
  "}, false);"
  "return xhr;"
  "},"
  "success:function(d, s) {"
  "console.log('success!')"
 "},"
 "error: function (a, b, c) {"
 "}"
 "});"
 "});"
 "</script>";

// REPLACE with your Domain name and URL path or IP address with path
const char* serverEnvio = "http://10.0.0.145/controle-acesso/?pagina=registro&action=insert";

// Servidor para coletar a data e hora atuais
// Veja mais em: https://lastminuteengineers.com/esp32-ntp-server-date-time-tutorial/
const char* ntpServer = "pool.ntp.org";
const long gmtOffset_sec = -3 * 3600;

unsigned long duration = 0;
int period = 2000;

// Define os pinos
#define TRANCA 4
#define CARD1 23
#define CARD2 22
#define CARD3 19
#define RED    5
#define GREEN 18

int cartao = 0;
int last_card = -1;

int fromWeb[2];

// Parse received JSON from web server
void parseJSON(String webGETdata){
  JSONVar myObject = JSON.parse(webGETdata);

  // JSON.typeof(jsonVar) can be used to get the type of the var
  if (JSON.typeof(myObject) == "undefined") {
    Serial.println("Parsing input failed!");
    return;
  }

  // myObject.keys() can be used to get an array of all the keys in the object
  JSONVar keys = myObject.keys();

  for (int i = 0; i < keys.length(); i++) {
    JSONVar value = myObject[keys[i]];
    Serial.print(i);
    Serial.print(keys[i]);
    Serial.print(" = ");
    Serial.println(value);
    fromWeb[i] = value;
  }
}

String parseLocalDate(){
  struct tm timeinfo;
  if (!getLocalTime(&timeinfo)) {
    Serial.println("Failed to obtain date");
    return ("Failed to obtain date");
  }
  char timeDay[3];
  char timeMonth[3];
  char timeYear[5];
  strftime(timeDay, 3, "%d", &timeinfo);
  strftime(timeMonth, 3, "%m", &timeinfo);
  strftime(timeYear, 5, "%Y", &timeinfo);
  return (String(timeYear) + '-' + String(timeMonth) + '-' + String(timeDay));
}

String parseLocalTime(){
  struct tm timeinfo;
  if (!getLocalTime(&timeinfo)) {
    Serial.println("Failed to obtain time");
    return ("Failed to obtain time");
  }
  char timeHour[3];
  char timeMinutes[3];
  char timeSeconds[3];
  strftime(timeHour, 3, "%H", &timeinfo);
  strftime(timeMinutes, 3, "%M", &timeinfo);
  strftime(timeSeconds, 3, "%S", &timeinfo);
  return (String(timeHour) + ':' + String(timeMinutes) + ':' + String(timeSeconds));
}

// By flagging a piece of code with the IRAM_ATTR attribute
// we are declaring that the compiled code will be placed
// in the Internal RAM (IRAM) of the ESP32.
void IRAM_ATTR togglePorta(int pin){
  Serial.println("Last card: ");
  Serial.println(last_card);
  if(pin == last_card || last_card == -1){
    Serial.println("Toggling...");
    if(digitalRead(TRANCA) == 1){
      digitalWrite(TRANCA, LOW); // Abrir porta (LED desligado)
      last_card = pin; // Apenas o último pode fechar
    }
    else{
      digitalWrite(TRANCA, HIGH); // Fechar porta (LED ligado)
      last_card = -1; // Qualquer um pode abrir
    }
    digitalWrite(GREEN, HIGH);
    delay(1500);
    digitalWrite(GREEN, LOW);
  }
  else {
    Serial.print("Não é o mesmo cartão que abriu a porta: ");
    notAuthorized();
  }
}

void notAuthorized(){
  Serial.println("Not authorized...");
  digitalWrite(RED, HIGH);
  delay(1500);
  digitalWrite(RED, LOW);
}

String httpGETRequest(const char* serverName){
  memset(fromWeb, 0, sizeof(fromWeb)); // reset authorization
  // Get and send data from web page
  HTTPClient http;
  //init and get the time
  configTime(gmtOffset_sec, 0, ntpServer);

  httpRequestData =
    "&date=" + String(parseLocalDate())
  + "&time=" + String(parseLocalTime())
  + "&cartao=" + String(cartao)
  + "";

  http.begin(serverName + httpRequestData);
  Serial.println(serverName + httpRequestData);

  // Send key to authenticate on server
  http.addHeader("Cookie", "PHPSESSID=3027436a2a4e44517d2446555c");

  // Send HTTP GET request
  httpResponseCode = http.GET();

  String payload = "{}";

  if (httpResponseCode > 0) {
    Serial.print("(GET) HTTP Response code: ");
    Serial.println(httpResponseCode);
    payload = http.getString();

    // Get page main content (JSON data)
    int openMainDiv = payload.indexOf("<div class=\"main\">");
    String offset = "<div class=\"main\">";
    int closeMainDiv = payload.indexOf("</div>", openMainDiv);

    payload = payload.substring(openMainDiv + offset.length(), closeMainDiv);
    payload.trim();
  }
  else {
    Serial.print("(GET) Error code: ");
    Serial.println(httpResponseCode);
    Serial.println("");
  }
  // Free resources
  http.end();

  return payload;
}

void setup(void) {

  Serial.begin(115200);

  pinMode(TRANCA, OUTPUT);
  pinMode(RED, OUTPUT);
  pinMode(GREEN, OUTPUT);
  pinMode(CARD1, INPUT);
  pinMode(CARD2, INPUT);
  pinMode(CARD3, INPUT);

  digitalWrite(TRANCA, HIGH);

  // Connect to WiFi network
  WiFi.begin(ssid, password);
  Serial.println("Connecting");

  // Wait for connection
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());


  // use mdns for host name resolution
  if (!MDNS.begin(host)) { // http://esp32
    Serial.println("Error setting up MDNS responder!");
    while (1) {
      delay(1000);
    }
  }
  Serial.println("mDNS responder started");
  // return index page which is stored in serverIndex
  server.on("/", HTTP_GET, []() {
    server.sendHeader("Connection", "close");
    server.send(200, "text/html", loginIndex);
  });
  server.on("/serverIndex", HTTP_GET, []() {
    server.sendHeader("Connection", "close");
    server.send(200, "text/html", serverIndex);
  });
  // handling uploading firmware file
  server.on("/update", HTTP_POST, []() {
    server.sendHeader("Connection", "close");
    server.send(200, "text/plain", (Update.hasError()) ? "FAIL" : "OK");
    ESP.restart();
  }, []() {
    HTTPUpload& upload = server.upload();
    if (upload.status == UPLOAD_FILE_START) {
      Serial.printf("Update: %s\n", upload.filename.c_str());
      if (!Update.begin(UPDATE_SIZE_UNKNOWN)) { //start with max available size
        Update.printError(Serial);
      }
    } else if (upload.status == UPLOAD_FILE_WRITE) {
      // flashing firmware to ESP
      if (Update.write(upload.buf, upload.currentSize) != upload.currentSize) {
        Update.printError(Serial);
      }
    } else if (upload.status == UPLOAD_FILE_END) {
      if (Update.end(true)) { //true to set the size to the current progress
        Serial.printf("Update Success: %u\nRebooting...\n", upload.totalSize);
      } else {
        Update.printError(Serial);
      }
    }
  });
  server.begin();
}

void loop(void) {
  if (WiFi.status() == WL_CONNECTED) { //Check WiFi connection status
  server.handleClient();

    if((unsigned long)(millis() - duration) > period){ // Run code below every [period] ms
      duration = millis();

      if(digitalRead(CARD1) == 1){ // Usuário com acesso a qualquer momento
        cartao = 1;
        if(digitalRead(TRANCA) == 0) {
          togglePorta(cartao); // Se porta estiver aberta, feche
        }
        else {
          String webGET = httpGETRequest(serverEnvio);
          Serial.print("GET response: ");
          Serial.println(webGET);

          parseJSON(webGET); // Sets fromWeb array
          boolean autorizado = fromWeb[0];
          if(autorizado == true) togglePorta(cartao);
          else notAuthorized();
        }
      }

      // Simulação de autorização
      // Tem que ter autorização no horário para o requisitante poder entrar
      if(digitalRead(CARD2)){ // Requisitante que pediu autorização

        cartao = 2;
        if(digitalRead(TRANCA) == 0) {
          togglePorta(cartao); // Se porta estiver aberta, feche
        }
        else { // Tem horário reservado? Checar no sistema web
          String webGET = httpGETRequest(serverEnvio);
          Serial.print("GET response: ");
          Serial.println(webGET);

          parseJSON(webGET); // Sets fromWeb array
          boolean autorizado = fromWeb[0];
          if(autorizado == true) togglePorta(cartao);
          else notAuthorized();
        }
      }
       if(digitalRead(CARD3)){ // Requisitante que pediu autorização
        cartao = 3;
        if(digitalRead(TRANCA) == 0) {
          togglePorta(cartao); // Se porta estiver aberta, feche
        }
        else { // Tem horário reservado? Checar no sistema web
          String webGET = httpGETRequest(serverEnvio);
          Serial.print("GET response: ");
          Serial.println(webGET);

          parseJSON(webGET); // Sets fromWeb array
          boolean autorizado = fromWeb[0];
          if(autorizado == true) togglePorta(cartao);
          else notAuthorized();
        }
      }

    }
  }
  else {
    Serial.println("WiFi Disconnected");
  }
}
