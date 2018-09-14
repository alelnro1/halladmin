openssl genrsa -out halldevlocal.key 1024
openssl req -x509 -config "C:\Program Files (x86)\GnuWin32\share\openssl.cnf" -new -key halldevlocal.key -subj "/C=AR/O=HallAdmin/CN=Certificado Hall 1/serialNumber=CUIT 20373561690" -out halldevlocal.csr
pause