import parametros as par
import mysql.connector as mariadb
import pandas as pd
from datetime import datetime as dt
from fastapi import FastAPI, Query
from fastapi.middleware.cors import CORSMiddleware

def extraer_informacion(id_empresa):
    """Extrae de la base de datos la información de colaboradores en la empresa seleccionada desde la página
    Args:
        id_empresa (int): id de la empresa seleccionada en la página
    Returns:
        df (pd.dataframe): dataframe con la información de los colaboradores en la empresa seleccionada"""
    mariadb_connection = mariadb.connect(user=par.usuario,
                                        password=par.password,
                                        host=par.host,
                                        port=par.puerto,
                                        database=par.bd)
    if mariadb_connection.is_connected():
        print("Se conecto bien")
    create_cursor = mariadb_connection.cursor()
    sql_statement = """SELECT   tipo_documento,fecha_nacimiento,genero,escolaridad,
                                estado_civil,categoria_licencia_auto,categoria_licencia_moto,
		                        capacitacion_seguridad_vial,siniestros,rol_accidente,
		                        cantidad_infracciones,tipo_infraccion,estado_pago,
		                        medio_transporte_trabajo,conductor_laboral,
                                tipo_vehiculo_conduce FROM pesv_colaboradores WHERE id_company = {}""".format(id_empresa)
    create_cursor.execute(sql_statement)
    result = create_cursor.fetchall()
    columns=['Tipo de documento','Fecha de nacimiento','Genero','Escolaridad',
             'Estado civil','Categoria licencia auto','Categoria licencia moto',
             'Capacitacion','Siniestros','Rol accidente','Cantidad infracciones',
             'Tipo infraccion','Estado pago','Medio transporte trabajo',
             'Conductor laboral','Tipo vehiculo conduce']
    df= pd.DataFrame(result, columns=columns)
    mariadb_connection.close()
    return df
def conversion_variables(df):
    """Realizamos la conversión de las variables númericas a categoricas para poder trabajar con ellas en las gráficas 
    con mayor facilidad y las variables de fecha a datetime para poder determinar la edad de los colaboradores en tiempo 
    real con gran facilidad.
    Args:
        df (pd.dataframe): dataframe con la información de los colaboradores en la empresa seleccionada
    Returns:
        df (pd.dataframe): dataframe con la información de los colaboradores en la empresa seleccionada con las 
                           variables convertidas a categoricas y datetime según corresponda"""
    df['Tipo de documento']=df.apply(lambda x:par.diccionario_tipo_documento[int(x['Tipo de documento'])],axis=1)
    #Pasamos a datatime la fecha de nacimiento para posteriormente obtener fácilmente la edad
    df['Fecha de nacimiento']=df.apply(lambda x:pd.to_datetime(str(x['Fecha de nacimiento'])),axis=1)
    df['Genero']=df.apply(lambda x:par.diccionario_genero[int(x['Genero'])],axis=1)
    df['Escolaridad']=df.apply(lambda x:par.diccionario_escolaridad[int(x['Escolaridad'])],axis=1)
    df['Estado civil']=df.apply(lambda x:par.diccionario_estado_civil[int(x['Estado civil'])],axis=1)
    df['Categoria licencia auto']=df.apply(lambda x:par.diccionario_categoria_licencia_auto[int(x['Categoria licencia auto'])],axis=1)
    df['Capacitacion']=df.apply(lambda x:par.diccionario_capacitacion_seguridad_vial[int(x['Capacitacion'])],axis=1)
    df['Siniestros']=df.apply(lambda x:par.diccionario_siniestros[int(x['Siniestros'])],axis=1)
    df['Tipo infraccion']=df.apply(lambda x:par.diccionario_tipo_infraccion[int(x['Tipo infraccion'])],axis=1)
    df['Estado pago']=df.apply(lambda x:par.diccionario_estado_pago[int(x['Estado pago'])],axis=1)
    df['Medio transporte trabajo']=df.apply(lambda x:
                                            par.diccionario_medio_transporte_trabajo[int(x['Medio transporte trabajo'])],axis=1)
    df['Conductor laboral']=df.apply(lambda x:par.diccionario_conductor_laboral[int(x['Conductor laboral'])],axis=1)
    df['Tipo vehiculo conduce']=df.apply(lambda x:par.diccionario_tipo_vehiculo_conduce[int(x['Tipo vehiculo conduce'])],axis=1)
    df["Edad"]=df.apply(lambda x:int((dt.now()-x["Fecha de nacimiento"]).days//365.25),axis=1)
    #Pasamos la edad a categorica para facilitar su visualizacion en las gráficas
    df["Edad"]=df.apply(lambda x: str(x["Edad"])+" años",axis=1)
    return df







app=FastAPI()
origins = [
    "http://localhost:5173",
    "http://190.249.137.176:65080",
    "http://172.16.100.11"
]
app.add_middleware(
    CORSMiddleware,
    allow_origins=origins,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.get("/api/tipo/documento")
async def get_documento(id_empresa: int = Query(170,description="ID de la empresa")):
    
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    print(df.head())
    df_tipo_documento=df.groupby(["Tipo de documento"])["total"].sum().reset_index()
    print(df_tipo_documento.head())
    total = df_tipo_documento["total"].sum()  
    df_tipo_documento["Porcentaje"] = df_tipo_documento.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_tipo_documento)):
        data_list.append({
            "name": df_tipo_documento.loc[i, "Tipo de documento"],
            "y": float(df_tipo_documento.loc[i, "Porcentaje"]),  
            "total": int(df_tipo_documento.loc[i, "total"])  
        })
    
    datos = {
        "colorByPoint": True,
        "data": data_list
    }
    return datos
@app.get("/api/edad")
async def get_edad(id_empresa: int = Query(170,description="ID de la empresa")):
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    df_edad=df.groupby(["Edad"])["total"].sum().reset_index()
    total = df_edad["total"].sum()  
    df_edad["Porcentaje"] = df_edad.apply(lambda x: (x["total"] / total) * 100, axis=1)
    
    name_list=[]
    data_list = []
    for i in range(len(df_edad)):
        name_list.append(df_edad.loc[i, "Edad"])
        data_list.append(int(df_edad.loc[i, "total"]))
    
    datos = {
        "categories": name_list,
        "data": data_list
    }
    return datos
@app.get("/api/genero")
async def get_genero(id_empresa: int = Query(170,description="ID de la empresa")):
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    df_genero=df.groupby(["Genero"])["total"].sum().reset_index()
    total = df_genero["total"].sum()  
    df_genero["Porcentaje"] = df_genero.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_genero)):
        data_list.append({
            "name": df_genero.loc[i, "Genero"],
            "y": float(df_genero.loc[i, "Porcentaje"]),  
            "total": int(df_genero.loc[i, "total"])  
        })
    
    datos = {
        "colorByPoint": True,
        "data": data_list
    }
    return datos
@app.get("/api/escolaridad")
async def get_escolaridad(id_empresa: int = Query(170,description="ID de la empresa")):
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    df_escolaridad=df.groupby(["Escolaridad"])["total"].sum().reset_index()
    total = df_escolaridad["total"].sum()  
    df_escolaridad["Porcentaje"] = df_escolaridad.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_escolaridad)):
        data_list.append({
            "name": df_escolaridad.loc[i, "Escolaridad"],
            "y": float(df_escolaridad.loc[i, "Porcentaje"]),  
            "total": int(df_escolaridad.loc[i, "total"])  
        })
    
    datos = {
        "colorByPoint": True,
        "data": data_list
    }
    return datos
@app.get("/api/estado/civil")
async def get_estado(id_empresa: int = Query(170,description="ID de la empresa")):
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    df_estado_civil=df.groupby(["Estado civil"])["total"].sum().reset_index()
    total = df_estado_civil["total"].sum()  
    df_estado_civil["Porcentaje"] = df_estado_civil.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_estado_civil)):
        data_list.append({
            "name": df_estado_civil.loc[i, "Estado civil"],
            "y": float(df_estado_civil.loc[i, "Porcentaje"]),  
            "total": int(df_estado_civil.loc[i, "total"])  
        })
    
    datos = {
        "colorByPoint": True,
        "data": data_list
    }
    return datos
@app.get("/api/categoria/licencia/auto")
async def get_categoria_auto(id_empresa: int = Query(170,description="ID de la empresa")):
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    df_licencia=df.groupby(["Categoria licencia auto"])["total"].sum().reset_index()
    total = df_licencia["total"].sum()  
    df_licencia["Porcentaje"] = df_licencia.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_licencia)):
        data_list.append({
            "name": df_licencia.loc[i, "Categoria licencia auto"],
            "y": float(df_licencia.loc[i, "Porcentaje"]),  
            "total": int(df_licencia.loc[i, "total"])  
        })
    
    datos = {
        "colorByPoint": True,
        "data": data_list
    }
    return datos
@app.get("/api/categoria/licencia/moto")
async def get_categoria_moto(id_empresa: int = Query(170,description="ID de la empresa")): 
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    df_licencia=df.groupby(["Categoria licencia moto"])["total"].sum().reset_index()
    total = df_licencia["total"].sum()  
    df_licencia["Porcentaje"] = df_licencia.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_licencia)):
        data_list.append({
            "name": df_licencia.loc[i, "Categoria licencia moto"],
            "y": float(df_licencia.loc[i, "Porcentaje"]),  
            "total": int(df_licencia.loc[i, "total"])  
        })
    
    datos = {
        "colorByPoint": True,
        "data": data_list
    }
    return datos
@app.get("/api/capacitacion")
async def get_capacitacion(id_empresa: int = Query(170,description="ID de la empresa")): 
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    df_capacitacion=df.groupby(["Capacitacion"])["total"].sum().reset_index()
    total = df_capacitacion["total"].sum()  
    df_capacitacion["Porcentaje"] = df_capacitacion.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_capacitacion)):
        data_list.append({
            "name": df_capacitacion.loc[i, "Capacitacion"],
            "y": float(df_capacitacion.loc[i, "Porcentaje"]),  
            "total": int(df_capacitacion.loc[i, "total"])  
        })
    
    datos = {
        "colorByPoint": True,
        "data": data_list
    }
    return datos
@app.get("/api/siniestros")
async def get_siniestros(id_empresa: int = Query(170,description="ID de la empresa")): 
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    df_siniestros=df.groupby(["Siniestros"])["total"].sum().reset_index()
    total = df_siniestros["total"].sum()  
    df_siniestros["Porcentaje"] = df_siniestros.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_siniestros)):
        data_list.append({
            "name": df_siniestros.loc[i, "Siniestros"],
            "y": float(df_siniestros.loc[i, "Porcentaje"]),  
            "total": int(df_siniestros.loc[i, "total"])  
        })
    
    datos = {
        "colorByPoint": True,
        "data": data_list
    }
    return datos
@app.get("/api/rol/siniestros")
async def get_rol(id_empresa: int = Query(170,description="ID de la empresa")): 
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    df_siniestros=df.copy()
    df_siniestros["Rol accidente"]=df_siniestros.apply(lambda x:"No se ha accidentado" if x["Siniestros"]=="No" else x["Rol accidente"],axis=1)
    df_rol=df_siniestros.groupby(["Rol accidente"])["total"].sum().reset_index()
    total = df_rol["total"].sum()  
    df_rol["Porcentaje"] = df_rol.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_rol)):
        data_list.append({
            "name": df_rol.loc[i, "Rol accidente"],
            "y": float(df_rol.loc[i, "Porcentaje"]),  
            "total": int(df_rol.loc[i, "total"])  
        })
    
    datos = {
        "colorByPoint": True,
        "data": data_list
    }
    return datos
@app.get("/api/cantidad/infracciones")
async def get_cantidad(id_empresa: int = Query(170,description="ID de la empresa")):
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    df_cantidad=df.groupby(["Cantidad infracciones"])["total"].sum().reset_index()
    total = df_cantidad["total"].sum()  
    df_cantidad["Porcentaje"] = df_cantidad.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    name_list=[]
    data_list = []
    for i in range(len(df_cantidad)):
        name_list.append(str(df_cantidad.loc[i, "Cantidad infracciones"]))
        data_list.append(int(df_cantidad.loc[i, "total"]))
    
    datos = {
        "categories": name_list,
        "data": data_list
    }
    return datos
@app.get("/api/tipo/infracciones")
async def get_tipo_infracciones(id_empresa: int = Query(170,description="ID de la empresa")):
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    df_infracciones=df.copy()
    df_infracciones["Tipo infraccion"]=df_infracciones.apply(lambda x:"No tiene infracciones" if x["Cantidad infracciones"]==0 else x["Tipo infraccion"],axis=1) 
    df_tipo_infracciones=df.groupby(["Tipo infraccion"])["total"].sum().reset_index()
    total = df_tipo_infracciones["total"].sum()  
    df_tipo_infracciones["Porcentaje"] = df_tipo_infracciones.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_tipo_infracciones)):
        data_list.append({
            "name": df_tipo_infracciones.loc[i, "Tipo infraccion"],
            "y": float(df_tipo_infracciones.loc[i, "Porcentaje"]),  
            "total": int(df_tipo_infracciones.loc[i, "total"])  
        })
    
    datos = {
        "colorByPoint": True,
        "data": data_list
    }
    return datos
@app.get("/api/estado/pago")
async def get_estado_pago(id_empresa: int = Query(170,description="ID de la empresa")):
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    df_infracciones=df.copy()
    df_infracciones["Estado pago"]=df_infracciones.apply(lambda x:"No tiene infracciones" if x["Cantidad infracciones"]==0 else x["Estado pago"],axis=1)
    df_estado_pago=df_infracciones.groupby(["Estado pago"])["total"].sum().reset_index()
    total = df_estado_pago["total"].sum()  
    df_estado_pago["Porcentaje"] = df_estado_pago.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_estado_pago)):
        data_list.append({
            "name": df_estado_pago.loc[i, "Estado pago"],
            "y": float(df_estado_pago.loc[i, "Porcentaje"]),  
            "total": int(df_estado_pago.loc[i, "total"])  
        })
    
    datos = {
        "colorByPoint": True,
        "data": data_list
    }
    return datos
@app.get("/api/medio/transporte")
async def get_medio_transporte(id_empresa: int = Query(170,description="ID de la empresa")): 
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    df_medio_transporte=df.groupby(["Medio transporte trabajo"])["total"].sum().reset_index()
    total = df_medio_transporte["total"].sum()  
    df_medio_transporte["Porcentaje"] = df_medio_transporte.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_medio_transporte)):
        data_list.append({
            "name": df_medio_transporte.loc[i, "Medio transporte trabajo"],
            "y": float(df_medio_transporte.loc[i, "Porcentaje"]),  
            "total": int(df_medio_transporte.loc[i, "total"])  
        })
    
    datos = {
        "colorByPoint": True,
        "data": data_list
    }
    return datos
@app.get("/api/conductor/laboral")
async def get_conductor_laboral(id_empresa: int = Query(170,description="ID de la empresa")): 
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    df_conductor_laboral=df.groupby(["Conductor laboral"])["total"].sum().reset_index()
    total = df_conductor_laboral["total"].sum()  
    df_conductor_laboral["Porcentaje"] = df_conductor_laboral.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_conductor_laboral)):
        data_list.append({
            "name": df_conductor_laboral.loc[i, "Conductor laboral"],
            "y": float(df_conductor_laboral.loc[i, "Porcentaje"]),  
            "total": int(df_conductor_laboral.loc[i, "total"])  
        })
    
    datos = {
        "colorByPoint": True,
        "data": data_list
    }
    return datos
@app.get("/api/tipo/vehiculo")
async def get_tipo_vehiculo(id_empresa: int = Query(170,description="ID de la empresa")): 
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    df_tipo_vehiculo = df.groupby(["Tipo vehiculo conduce"])["total"].sum().reset_index()
    total = df_tipo_vehiculo["total"].sum()  
    
    df_tipo_vehiculo["Porcentaje"] = df_tipo_vehiculo.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_tipo_vehiculo)):
        data_list.append({
            "name": df_tipo_vehiculo.loc[i, "Tipo vehiculo conduce"],
            "y": float(df_tipo_vehiculo.loc[i, "Porcentaje"]),  
            "total": int(df_tipo_vehiculo.loc[i, "total"])  
        })
    
    datos = {
        "colorByPoint": True,
        "data": data_list
    }
    
    return datos
@app.get("/api/comparativo")
async def get_comparativo(id_empresa: int = Query(170,description="ID de la empresa")): 
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Creamos una columna para contar la cantidad de cada uno de los items en todas 
    #las variables categoricas una vez estemos devolviendo la información al frontend
    df["total"]=1
    def rango_edad(edad):
        if edad < 25:
            return '< 25 años'
        elif 25 <= edad < 30:
            return '25-29 años'
        elif 30 <= edad < 35:
            return '30-34 años'
        elif 35 <= edad < 40:
            return '35-39 años'
        elif 40 <= edad < 45:
            return '40-44 años'
        elif 45 <= edad < 50:
            return '45-49 años'
        elif 50 <= edad < 55:
            return '50-54 años'
        elif 55 <= edad < 60:
            return '55-59 años'
        else:
            return '+ 60 años'
    df["Rango edad"]=df.apply(lambda x:rango_edad(int(x["Edad"].split()[0])),axis=1)
    df["Porcentaje Siniestros"]=df.apply(lambda x:1 if x["Siniestros"]=="Si" else 0,axis=1)
    df["Porcentaje infracciones"]=df.apply(lambda x:1 if x["Cantidad infracciones"]>0 else 0,axis=1)
    def funcion_agregacion(series):
        series_sum = series.sum()
        series_count = series.count()
        return series_sum / series_count *100 if series_count > 0 else 0
    df_siniestros=df.groupby(["Rango edad"])["Porcentaje Siniestros"].agg(funcion_agregacion).reset_index()
    df_infracciones=df.groupby(["Rango edad"])["Porcentaje infracciones"].agg(funcion_agregacion).reset_index()
    name_list=['< 25 años','25-29 años','30-34 años','35-39 años','40-44 años',
               '45-49 años','50-54 años','55-59 años','+ 60 años']
    siniestros_list = []
    infracciones_list=[]
    for elemento in name_list:
        if elemento in df_siniestros["Rango edad"].values:
            siniestros_list.append(round(float(df_siniestros[df_siniestros["Rango edad"]==elemento]["Porcentaje Siniestros"].iloc[0]), 2))
        else:
            siniestros_list.append(0)
        if elemento in df_infracciones["Rango edad"].values:
            infracciones_list.append(round(float(df_infracciones[df_infracciones["Rango edad"]==elemento]["Porcentaje infracciones"].iloc[0]),2))
        else:
            infracciones_list.append(0)
    
    datos = {
        "categories": name_list,
        "data": [{"name":"Siniestros","data":siniestros_list},
                 {"name":"Infracciones","data":infracciones_list}]
    }
    return datos
@app.get("/api/informe/excel")
async def get_informe(id_empresa: int = Query(170,description="ID de la empresa")):
    df=extraer_informacion(id_empresa)
    df=conversion_variables(df)
    #Generamos un diccionario para el informe excel
    data_excel={}
    """data para el tipo de documento"""
    df["total"]=1
    df_tipo_documento=df.groupby(["Tipo de documento"])["total"].sum().reset_index()
    total = df_tipo_documento["total"].sum()  
    df_tipo_documento["Porcentaje"] = df_tipo_documento.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_tipo_documento)):
        data_list.append({
            "Tipo de documento": df_tipo_documento.loc[i, "Tipo de documento"],
            "Porcentaje": round(float(df_tipo_documento.loc[i, "Porcentaje"]),4),  
            "Numero de personajes": int(df_tipo_documento.loc[i, "total"])  
        })
    
    data_excel["Tipo de documento"]=data_list
    """data para la edad"""
    df_edad=df.groupby(["Edad"])["total"].sum().reset_index()
    total = df_edad["total"].sum()  
    df_edad["Porcentaje"] = df_edad.apply(lambda x: (x["total"] / total) * 100, axis=1)
    
    data_list = []
    for i in range(len(df_edad)):
        data_list.append({
            "Edad": df_edad.loc[i, "Edad"],
            "Porcentaje":round(float(df_edad.loc[i, "Porcentaje"]),4),
            "Numero de personas": int(df_edad.loc[i, "total"])
            })
    data_excel["Edad"]=data_list
    """data para el genero"""
    df_genero=df.groupby(["Genero"])["total"].sum().reset_index()
    total = df_genero["total"].sum()  
    df_genero["Porcentaje"] = df_genero.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_genero)):
        data_list.append({
            "Genero": df_genero.loc[i, "Genero"],
            "Porcentaje": round(float(df_genero.loc[i, "Porcentaje"]),4),  
            "Numero de personas": int(df_genero.loc[i, "total"])  
        })
    data_excel["Genero"]=data_list
    """data para la escolaridad"""
    df_escolaridad=df.groupby(["Escolaridad"])["total"].sum().reset_index()
    total = df_escolaridad["total"].sum()  
    df_escolaridad["Porcentaje"] = df_escolaridad.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_escolaridad)):
        data_list.append({
            "Escolaridad": df_escolaridad.loc[i, "Escolaridad"],
            "Porcentaje": round(float(df_escolaridad.loc[i, "Porcentaje"]),4),  
            "Numero de personas": int(df_escolaridad.loc[i, "total"])  
        })
    data_excel["Escolaridad"]=data_list
    """data para el estado civil"""
    df_estado_civil=df.groupby(["Estado civil"])["total"].sum().reset_index()
    total = df_estado_civil["total"].sum()  
    df_estado_civil["Porcentaje"] = df_estado_civil.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_estado_civil)):
        data_list.append({
            "Estado civil": df_estado_civil.loc[i, "Estado civil"],
            "Porcentaje": round(float(df_estado_civil.loc[i, "Porcentaje"]),4),  
            "Numero de personas": int(df_estado_civil.loc[i, "total"])  
        })
    data_excel["Estado civil"]=data_list
    """data para la categoria licencia auto"""
    df_licencia=df.groupby(["Categoria licencia auto"])["total"].sum().reset_index()
    total = df_licencia["total"].sum()  
    df_licencia["Porcentaje"] = df_licencia.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_licencia)):
        data_list.append({
            "Categoria de licencia": df_licencia.loc[i, "Categoria licencia auto"],
            "Porcentaje": round(float(df_licencia.loc[i, "Porcentaje"]),4),  
            "Numero de personas": int(df_licencia.loc[i, "total"])  
        })
    data_excel["Categoria licencia auto"]=data_list
    """data para la categoria licencia moto"""
    df_licencia=df.groupby(["Categoria licencia moto"])["total"].sum().reset_index()
    total = df_licencia["total"].sum()  
    df_licencia["Porcentaje"] = df_licencia.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_licencia)):
        data_list.append({
            "Categoria de licencia": df_licencia.loc[i, "Categoria licencia moto"],
            "Porcentaje": round(float(df_licencia.loc[i, "Porcentaje"]),4),  
            "Numero de personas": int(df_licencia.loc[i, "total"])  
        })
    data_excel["Categoria licencia moto"]=data_list
    """data para la capacitacion"""
    df_capacitacion=df.groupby(["Capacitacion"])["total"].sum().reset_index()
    total = df_capacitacion["total"].sum()  
    df_capacitacion["Porcentaje"] = df_capacitacion.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_capacitacion)):
        data_list.append({
            "Capacitación": df_capacitacion.loc[i, "Capacitacion"],
            "Porcentaje": round(float(df_capacitacion.loc[i, "Porcentaje"]),4),  
            "Numero de personas": int(df_capacitacion.loc[i, "total"])  
        })
    data_excel["Capacitacion"]=data_list
    """data para los siniestros"""
    df_siniestros=df.groupby(["Siniestros"])["total"].sum().reset_index()
    total = df_siniestros["total"].sum()  
    df_siniestros["Porcentaje"] = df_siniestros.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_siniestros)):
        data_list.append({
            "Siniestros": df_siniestros.loc[i, "Siniestros"],
            "Porcentaje": round(float(df_siniestros.loc[i, "Porcentaje"]),4),  
            "Numero de personas": int(df_siniestros.loc[i, "total"])  
        })
    data_excel["Siniestros"]=data_list
    """data para el rol accidente"""
    df_siniestros=df.copy()
    df_siniestros["Rol accidente"]=df_siniestros.apply(lambda x:"No se ha accidentado" if x["Siniestros"]=="No" else x["Rol accidente"],axis=1)
    df_rol=df_siniestros.groupby(["Rol accidente"])["total"].sum().reset_index()
    total = df_rol["total"].sum()  
    df_rol["Porcentaje"] = df_rol.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_rol)):
        data_list.append({
            "Rol accidente": df_rol.loc[i, "Rol accidente"],
            "Porcentaje": round(float(df_rol.loc[i, "Porcentaje"]),4),  
            "Numero de personas": int(df_rol.loc[i, "total"])  
        })
    data_excel["Rol accidente"]=data_list
    """data para la cantidad de infracciones"""
    df_cantidad=df.groupby(["Cantidad infracciones"])["total"].sum().reset_index()
    total = df_cantidad["total"].sum()  
    df_cantidad["Porcentaje"] = df_cantidad.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_cantidad)):
        data_list.append({
            "Cantidad infracciones":str(df_cantidad.loc[i, "Cantidad infracciones"]),
            "Porcentaje": round(float(df_cantidad.loc[i, "Porcentaje"]),4),
            "Numero de personas": int(df_cantidad.loc[i, "total"])})
    data_excel["Cantidad infracciones"]=data_list
    """data para el tipo de infracciones"""
    df_infracciones=df.copy()
    df_infracciones["Tipo infraccion"]=df_infracciones.apply(lambda x:"No tiene infracciones" if x["Cantidad infracciones"]==0 else x["Tipo infraccion"],axis=1) 
    df_tipo_infracciones=df.groupby(["Tipo infraccion"])["total"].sum().reset_index()
    total = df_tipo_infracciones["total"].sum()  
    df_tipo_infracciones["Porcentaje"] = df_tipo_infracciones.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_tipo_infracciones)):
        data_list.append({
            "Tipo de infracción": df_tipo_infracciones.loc[i, "Tipo infraccion"],
            "Porcentaje": round(float(df_tipo_infracciones.loc[i, "Porcentaje"]),4),  
            "Numero de personas": int(df_tipo_infracciones.loc[i, "total"])  
        })
    data_excel["Tipo infracciones"]=data_list
    """data para el estado de pago"""
    df_infracciones=df.copy()
    df_infracciones["Estado pago"]=df_infracciones.apply(lambda x:"No tiene infracciones" if x["Cantidad infracciones"]==0 else x["Estado pago"],axis=1)
    df_estado_pago=df_infracciones.groupby(["Estado pago"])["total"].sum().reset_index()
    total = df_estado_pago["total"].sum()  
    df_estado_pago["Porcentaje"] = df_estado_pago.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_estado_pago)):
        data_list.append({
            "Estado de pago": df_estado_pago.loc[i, "Estado pago"],
            "Porcentaje": round(float(df_estado_pago.loc[i, "Porcentaje"]),4),  
            "Numero de personas": int(df_estado_pago.loc[i, "total"])  
        })
    data_excel["Estado pago"]=data_list
    """data para el medio de transporte"""
    df_medio_transporte=df.groupby(["Medio transporte trabajo"])["total"].sum().reset_index()
    total = df_medio_transporte["total"].sum()  
    df_medio_transporte["Porcentaje"] = df_medio_transporte.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_medio_transporte)):
        data_list.append({
            "Medio de transporte para ir al trabajo": df_medio_transporte.loc[i, "Medio transporte trabajo"],
            "Porcentaje": round(float(df_medio_transporte.loc[i, "Porcentaje"]),4),  
            "Numero de personas": int(df_medio_transporte.loc[i, "total"])  
        })
    data_excel["Medio transporte trabajo"]=data_list
    """data para el conductor laboral"""
    df_conductor_laboral=df.groupby(["Conductor laboral"])["total"].sum().reset_index()
    total = df_conductor_laboral["total"].sum()  
    df_conductor_laboral["Porcentaje"] = df_conductor_laboral.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_conductor_laboral)):
        data_list.append({
            "Conductor laboral": df_conductor_laboral.loc[i, "Conductor laboral"],
            "Porcentaje": round(float(df_conductor_laboral.loc[i, "Porcentaje"]),4),  
            "Numero de personas": int(df_conductor_laboral.loc[i, "total"])  
        })
    data_excel["Conductor laboral"]=data_list
    """data para el tipo de vehiculo laboral"""
    df_tipo_vehiculo = df.groupby(["Tipo vehiculo conduce"])["total"].sum().reset_index()
    total = df_tipo_vehiculo["total"].sum()  
    
    df_tipo_vehiculo["Porcentaje"] = df_tipo_vehiculo.apply(lambda x: (x["total"] / total) * 100, axis=1)
    

    data_list = []
    for i in range(len(df_tipo_vehiculo)):
        data_list.append({
            "Tipo de vehiculo laboral": df_tipo_vehiculo.loc[i, "Tipo vehiculo conduce"],
            "Porcentaje": round(float(df_tipo_vehiculo.loc[i, "Porcentaje"]),4),  
            "Numero de personas": int(df_tipo_vehiculo.loc[i, "total"])  
        })
    data_excel["Tipo vehiculo laboral"]=data_list
    return data_excel
