using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;


using LukaKompControlPanel.Klase;
using System.Threading;

namespace LukaKompControlPanel
{
    public partial class DodajKomponenteMenu : Form
    {
        Thread th; 
        public DodajKomponenteMenu()
        {
            InitializeComponent();
        }

        //Dodaj graficku
        private void button1_Click(object sender, EventArgs e)
        {
            List < controlInfo > lista = new List<controlInfo>();
            lista.Add(new controlInfo("textBox", "Ime",false));
            lista.Add(new controlInfo("richTextBox", "opis", false));
            lista.Add(new controlInfo("textBox", "tipMemorije", true));
            lista.Add(new controlInfo("textBox", "vram",true));
            lista.Add(new controlInfo("slika", "slika", false));
            lista.Add(new controlInfo("proizvodjac", "proizvodjac", false));
            lista.Add(new controlInfo("textBoxBroj", "cena", false));
            lista.Add(new controlInfo("textBoxBroj", "kolicina", false));
            napraviThreadIUgasiSe(lista, "komponente", "graficku","graficke");
        }
        //Dodaj procesor
        private void button2_Click(object sender, EventArgs e)
        {
            List<controlInfo> lista = new List<controlInfo>();
            lista.Add(new controlInfo("textBox", "Ime", false));
            lista.Add(new controlInfo("richTextBox", "opis", false));
            lista.Add(new controlInfo("textBox", "brzina", true));
            lista.Add(new controlInfo("trueFalse", "overclock", true));
            lista.Add(new controlInfo("textBox", "socket", true));
            lista.Add(new controlInfo("textBoxBroj", "broj_jezgara", true));
            lista.Add(new controlInfo("slika", "slika", false));
            lista.Add(new controlInfo("proizvodjac", "proizvodjac", false));
            lista.Add(new controlInfo("textBoxBroj", "cena", false));
            lista.Add(new controlInfo("textBoxBroj", "kolicina", false));
            napraviThreadIUgasiSe(lista, "komponente", "procesor","procesori");
        }
        //Dodaj proizvodjaca
        private void button3_Click(object sender, EventArgs e)
        {
            List<controlInfo> lista = new List<controlInfo>();
            lista.Add(new controlInfo("textBox", "Ime",false));
            lista.Add(new controlInfo("slika", "slika",false));
            napraviThreadIUgasiSe(lista, "proizvodjac", "proizvodjaca","proizvodjac");
        }
        private void napraviThreadIUgasiSe(List<controlInfo> lista, string tip, string gramatickiIspravanTip,string tabela)
        {
            th = new Thread(() => ucitajFormu(lista, tip, gramatickiIspravanTip,tabela));
            th.SetApartmentState(ApartmentState.STA);
            th.Start();
        }

        public void ucitajFormu(List<controlInfo> lista,string tip,string gramatickiIspravanTip,string tabela)
        {
            var forma = new DodajKomponentu(lista,tip, gramatickiIspravanTip,tabela);
            Application.Run(forma);
        }

        private void DodajKomponenteMenu_Load(object sender, EventArgs e)
        {
            foreach (Control c in this.Controls)
            {
                c.Font = new Font(Design.fonts.Families[0], 20f, FontStyle.Regular);
            }
            label1.Font = new Font(Design.fonts.Families[0], 40);
        }

        private void button4_Click(object sender, EventArgs e)
        {
            List<controlInfo> lista = new List<controlInfo>();
            lista.Add(new controlInfo("textBox", "Ime", false));
            lista.Add(new controlInfo("richTextBox", "opis", false));
            //
            lista.Add(new controlInfo("textBox", "socket", true));
            lista.Add(new controlInfo("textBox", "tip_memorije", true));
            lista.Add(new controlInfo("textBox", "chipset", true));
            lista.Add(new controlInfo("textBox", "broj_ram_slotova", true));
            //
            lista.Add(new controlInfo("slika", "slika", false));
            lista.Add(new controlInfo("proizvodjac", "proizvodjac", false));
            lista.Add(new controlInfo("textBoxBroj", "cena", false));
            lista.Add(new controlInfo("textBoxBroj", "kolicina", false));
            napraviThreadIUgasiSe(lista, "komponente", "maticnu", "maticne");
        }

        private void button5_Click(object sender, EventArgs e)
        {
            List<controlInfo> lista = new List<controlInfo>();
            lista.Add(new controlInfo("textBox", "Ime", false));
            lista.Add(new controlInfo("richTextBox", "opis", false));
            //
            lista.Add(new controlInfo("textBox", "kapacitet", true));
            lista.Add(new controlInfo("textBox", "tip_memorije", true));
            lista.Add(new controlInfo("textBox", "frekvencija", true));
            //
            lista.Add(new controlInfo("slika", "slika", false));
            lista.Add(new controlInfo("proizvodjac", "proizvodjac", false));
            lista.Add(new controlInfo("textBoxBroj", "cena", false));
            lista.Add(new controlInfo("textBoxBroj", "kolicina", false));
            napraviThreadIUgasiSe(lista, "komponente", "RAM", "ram");
        }

        private void button6_Click(object sender, EventArgs e)
        {
            List<controlInfo> lista = new List<controlInfo>();
            lista.Add(new controlInfo("textBox", "Ime", false));
            lista.Add(new controlInfo("richTextBox", "opis", false));
            //
            lista.Add(new controlInfo("textBox", "kapacitet", true));
            lista.Add(new controlInfo("textBox", "tip", true));
            //
            lista.Add(new controlInfo("slika", "slika", false));
            lista.Add(new controlInfo("proizvodjac", "proizvodjac", false));
            lista.Add(new controlInfo("textBoxBroj", "cena", false));
            lista.Add(new controlInfo("textBoxBroj", "kolicina", false));
            napraviThreadIUgasiSe(lista, "komponente", "disk", "disk");
        }

        private void button7_Click(object sender, EventArgs e)
        {
            List<controlInfo> lista = new List<controlInfo>();
            lista.Add(new controlInfo("textBox", "Ime", false));
            lista.Add(new controlInfo("richTextBox", "opis", false));
            //
            lista.Add(new controlInfo("textBox", "snaga", true));
            //
            lista.Add(new controlInfo("slika", "slika", false));
            lista.Add(new controlInfo("proizvodjac", "proizvodjac", false));
            lista.Add(new controlInfo("textBoxBroj", "cena", false));
            lista.Add(new controlInfo("textBoxBroj", "kolicina", false));
            napraviThreadIUgasiSe(lista, "komponente", "napajanje", "napajanje");
        }                                          

        private void button8_Click(object sender, EventArgs e)
        {
            List<controlInfo> lista = new List<controlInfo>();
            lista.Add(new controlInfo("textBox", "Ime", false));
            lista.Add(new controlInfo("richTextBox", "opis", false));
            //
            lista.Add(new controlInfo("textBox", "kompatibilnost", true));
            //
            lista.Add(new controlInfo("slika", "slika", false));
            lista.Add(new controlInfo("proizvodjac", "proizvodjac", false));
            lista.Add(new controlInfo("textBoxBroj", "cena", false));
            lista.Add(new controlInfo("textBoxBroj", "kolicina", false));
            napraviThreadIUgasiSe(lista, "komponente", "kuciste", "kuciste");
        }

        private void exitButton_Click(object sender, EventArgs e)
        {
            this.Close();
        }

        Point lastPoint;
        private void DodajKomponenteMenu_MouseMove(object sender, MouseEventArgs e)
        {
            if (e.Button == MouseButtons.Left)
            {
                this.Left += e.X - lastPoint.X;
                this.Top += e.Y - lastPoint.Y;
            }
        }

        private void DodajKomponenteMenu_MouseDown(object sender, MouseEventArgs e)
        {
            lastPoint = new Point(e.X, e.Y);
        }



        /*
public void ucitajFormu<T>(List<controlInfo> lista) where T : Form, new()
{
var forma = Activator.CreateInstance(typeof(T), new object[] { lista }) as T;
Application.Run(forma);
}
*/
    }
}
