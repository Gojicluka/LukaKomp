using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace LukaKompControlPanel.Klase
{
    public class controlInfo
    {
        public string tip { get; set; }
        public string tekst { get; set; }
        public bool atribut{ get; set; }

        public controlInfo(string tip,string tekst,bool atribut)
        {
            this.tip = tip;
            this.tekst = tekst;
            this.atribut = atribut;
        }
    }
}
