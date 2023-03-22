using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

using LukaKompControlPanel.Models;
using LukaKompControlPanel.Klase;

namespace LukaKompControlPanel
{
    public partial class Dashboard : Form
    {
        Point lastPoint;
        int id;
        string username, email;

        public Dashboard(int id,string username, string email)
        {
            InitializeComponent();

            this.id = id;
            this.username = username;
            this.email = email;

            label2.Text = username;
        }


        private void buttonDodajKomponentu_Click(object sender, EventArgs e)
        {
            DodajKomponenteMenu forma =  new DodajKomponenteMenu();
            forma.Show();
        }

        //Promeni folder slika click
        private async void button2_Click(object sender, EventArgs e)
        {
            FolderBrowserDialog path = new FolderBrowserDialog();

            if (path.ShowDialog() == DialogResult.OK && !string.IsNullOrWhiteSpace(path.SelectedPath))
            {
                string selectedPathTemp = path.SelectedPath+@"\";

                await dataAccess.SaveDataAsync<dynamic>("Update `imagefolderpath` set `imagePath`=@imgPath", new { imgPath = selectedPathTemp }, Helper.CnnVal("LukaKomp"));
                
                MessageBox.Show("Uspesno promenjen path");
            }

            path.Dispose();
        }

        private void buttonPromeniPrivilegije_Click(object sender, EventArgs e)
        {
            Privilegije forma = new Privilegije();
            forma.Show();
        }

        private void konfiguratorButton_Click(object sender, EventArgs e)
        {
            Konfigurator forma = new Konfigurator();
            forma.Show();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            potvrdaDostave forma = new potvrdaDostave();
            forma.Show();
        }

        private void exitButton_Click(object sender, EventArgs e)
        {
            this.Close();
        }

        private void Dashboard_MouseMove(object sender, MouseEventArgs e)
        {
            if (e.Button == MouseButtons.Left)
            {
                this.Left += e.X - lastPoint.X;
                this.Top += e.Y - lastPoint.Y;
            }
        }

        private void Dashboard_MouseDown(object sender, MouseEventArgs e)
        {
            lastPoint = new Point(e.X, e.Y);
        }

        private void button4_Click(object sender, EventArgs e)
        {
            Statistika forma = new Statistika();
            forma.Show();
        }

        private void button3_Click(object sender, EventArgs e)
        {
            promenaKomponenata forma = new promenaKomponenata();
            forma.Show();
        }

        private void Dashboard_Load(object sender, EventArgs e)
        {
            List<racun> racuni = new List<racun>();
            racuni = dataAccess.LoadData<racun, dynamic>
                ("SELECT SUM(medj.kolicina) as ukProdato,SUM(kom.cena*medj.kolicina) as ukcena FROM racun " +
                "INNER JOIN racun_medjutabela as medj ON racun.id = medj.idracun " +
                "INNER JOIN komponente as kom ON medj.idDrugeTabele = kom.id ",new { },Helper.CnnVal("LukaKomp"));

          
            foreach (Control c in this.Controls)
            {
                c.Font = new Font(Design.fonts.Families[0], 18f, FontStyle.Regular);
            }
            label3.Font = new Font(Design.fonts.Families[0], 50);
        }
    }
}
