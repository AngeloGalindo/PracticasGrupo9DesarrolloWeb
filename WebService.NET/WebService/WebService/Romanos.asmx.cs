using System;
using System.Collections;
using System.ComponentModel;
using System.Data;
using System.Linq;
using System.Web;
using System.Web.Services;
using System.Web.Services.Protocols;
using System.Xml.Linq;

namespace wsRomanos
{
    /// <summary>
    /// Summary description for Service1
    /// </summary>
    [WebService(Namespace = "http://tempuri.org/")]
    [WebServiceBinding(ConformsTo = WsiProfiles.BasicProfile1_1)]
    [ToolboxItem(false)]
    // To allow this Web Service to be called from script, using ASP.NET AJAX, uncomment the following line. 
    // [System.Web.Script.Services.ScriptService]
    public class Romanos : System.Web.Services.WebService
    {

        [WebMethod]
        public string HelloWorld()
        {
            return "Hello World";
        }

        [WebMethod]
        public string getRomano(string arabigo)
        {
            string retorno = "0";
            switch (arabigo.Trim())
            {
                case "1":
                    retorno = "I";
                    break;
                case "2":
                    retorno = "II";
                    break;
                case "3":
                    retorno = "III";
                    break;
                case "4":
                    retorno = "IV";
                    break;
                case "5":
                    retorno = "V";
                    break;
                case "6":
                    retorno = "VI";
                    break;
                case "7":
                    retorno = "VII";
                    break;
                case "8":
                    retorno = "VIII";
                    break;
                case "9":
                    retorno = "IX";
                    break;
                case "10":
                    retorno = "X";
                    break;
                default:
                    retorno = "No definido";
                    break;
            }//switch
            return retorno;
        }//public
    }
}
