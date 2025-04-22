const express = require("express");
const fs = require("fs");
const xml2js = require("xml2js");
const path = require("path");
const app = express();
const PORT = 3000;

const JSON_PATH = "./data/records.json";
const XML_PATH = "./data/records.xml";

app.get("/api/records.json", (req, res) => {
  if (!fs.existsSync(JSON_PATH)) return res.status(404).send("JSON файл не знайдено.");
  res.type("application/json").send(fs.readFileSync(JSON_PATH));
});

app.get("/api/records.xml", (req, res) => {
  if (!fs.existsSync(XML_PATH)) return res.status(404).send("XML файл не знайдено.");
  res.type("application/xml").send(fs.readFileSync(XML_PATH));
});

app.delete("/api/records/:id", (req, res) => {
    let records = readJson();
    const index = records.findIndex(r => r.id == req.params.id);
    if (index === -1) return res.status(404).json({ message: "Запис не знайдено" });
  
    const deleted = records.splice(index, 1)[0];
    writeJson(records);
    writeXml(records);
    res.json({ message: "Запис видалено", record: deleted });
  });
  

app.use(express.json());
app.use(express.static("public"));

function readJson() {
  if (!fs.existsSync(JSON_PATH)) return [];
  return JSON.parse(fs.readFileSync(JSON_PATH));
}

function writeJson(data) {
  fs.writeFileSync(JSON_PATH, JSON.stringify(data, null, 2));
}

function writeXml(data) {
  const builder = new xml2js.Builder();
  const xml = builder.buildObject({ records: { record: data } });
  fs.writeFileSync(XML_PATH, xml);
}

app.post("/api/records", (req, res) => {
  const records = readJson();
  const newRecord = { id: Date.now(), ...req.body };
  records.push(newRecord);
  writeJson(records);
  writeXml(records);
  res.json({ message: "Запис створено", record: newRecord });
});

app.get("/api/records", (req, res) => {
  res.json(readJson());
});

app.put("/api/records/:id", (req, res) => {
  const records = readJson();
  const index = records.findIndex(r => r.id == req.params.id);
  if (index === -1) return res.status(404).json({ message: "Запис не знайдено" });

  records[index] = { ...records[index], ...req.body };
  writeJson(records);
  writeXml(records);
  res.json({ message: "Запис оновлено", record: records[index] });
});

app.delete("/api/records/:id", (req, res) => {
  let records = readJson();
  const index = records.findIndex(r => r.id == req.params.id);
  if (index === -1) return res.status(404).json({ message: "Запис не знайдено" });

  const deleted = records.splice(index, 1)[0];
  writeJson(records);
  writeXml(records);
  res.json({ message: "Запис видалено", record: deleted });
});

app.listen(PORT, () => console.log(`Сервер працює на http://localhost:${PORT}`));
