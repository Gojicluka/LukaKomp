using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.Collections;

using LukaKompControlPanel.Klase;
using LukaKompControlPanel.Models;

//Dodati submit i dodati sql unos konaco

//Upload multiple slika

//Dodati multiple pictureBoxeve
//OBJEKAT ZA PROIZVODJACA

namespace LukaKompControlPanel
{

    public partial class DodajKomponentu : Form
    {

        List<controlInfo> controlInfoLista = new List<controlInfo>();

        string tip = "";
        string gramatickiIspravanTip = "";
        string folderPathImage = "";
        string tabela = "";

        List<TextBox> textBoxevi = new List<TextBox>();
        List<Label> labeli = new List<Label>();
        List<ComboBox> comboBoxevi = new List<ComboBox>();
        List<PictureBox> pictureBoxevi = new List<PictureBox>();
        List<Button> dugmici = new List<Button>();

        Label naslov = new Label();

        List<string> filesToUpload = new List<string>();

        public DodajKomponentu(List<controlInfo> lista,string tip,string gramatickiIspravanTip,string tabela)
        {
            this.controlInfoLista = lista;
            this.gramatickiIspravanTip = gramatickiIspravanTip;
            this.tip = tip;
            this.tabela = tabela;
            InitializeComponent();
        }
        private void DodajKomponentu_Load(object sender, EventArgs e)
        {
            folderPathImage = getFolderPath();
            instancirajKomponente();


            foreach (Control c in this.Controls)
            {
                c.Font = new Font(Design.fonts.Families[0], 12f, FontStyle.Regular);
            }
            naslov.Font = new Font(Design.fonts.Families[0], 25, FontStyle.Bold);
            foreach(Button dugme in dugmici)
            {
                dugme.Font = new Font(Design.fonts.Families[0], 14);
            }
           
        }

        private string getFolderPath()
        {
            //Ne treba nam async jer imamo samo jedan string koji se returnuje i vraca se odma 
            List<imageFolderPath> listaPodataka = new List<imageFolderPath>();
            listaPodataka = dataAccess.LoadData<imageFolderPath, dynamic>(
                "select * from imagefolderpath limit 1",
                new { },
                Helper.CnnVal("LukaKomp"));
            return listaPodataka[0].imagePath;
            
        }

        
        //Unosimo u bazu
        private async void dugmeSubmitKliknuto(object sender, EventArgs e)
        {
            //comboBox.selectedValue
            int textBoxBrojac = 0;
            int comboBoxBrojac = 0;

            IDictionary<string,object> unosDictionary = new System.Dynamic.ExpandoObject();
            //unosDictionary[controlInfoLista[0].tekst] = "zdravo";

            string sveKolone = "";
            string sveKoloneParametri = "";

            if(this.tip =="komponente")
            {
                sveKolone += "tip";
                sveKoloneParametri += "@tip";
                unosDictionary["tip"] = this.tabela;
            }
            
            bool unesenoSve = true;
            //unosDictionary[controlInfoLista[0].tekst] = textBoxevi[textBoxBrojac].Text;
            //unosDictionary["Ime"] = "Luka";

            
            for (int i =0;i<controlInfoLista.Count;i++)
            {
                
                if(controlInfoLista[i].atribut ) {      
                    if(!sveKolone.Contains("atributi"))
                    {
                        //Dodajemo komponentama stalno zarez jer vec krecu sa tipom koji je unesen
                        if (i != 0||this.tip=="komponente") { sveKolone += ","; sveKoloneParametri += ","; }
                        sveKolone += "atributi";
                        sveKoloneParametri += "@atributi";
                    }
                }
                else
                {
                    if (i != 0 || this.tip == "komponente") { sveKolone += ","; sveKoloneParametri += ","; }
                    sveKolone += controlInfoLista[i].tekst;
                    sveKoloneParametri += "@" + controlInfoLista[i].tekst;
                }
                
                switch (controlInfoLista[i].tip)
                {
                    case "textBoxBroj":
                    case "textBox":
                    case "richTextBox":
                        if (textBoxevi[textBoxBrojac].Text == "") { unesenoSve = false;break; }
                        if (controlInfoLista[i].atribut)
                        {
                            if (unosDictionary.ContainsKey("atributi")) unosDictionary["atributi"] += $"|{controlInfoLista[i].tekst}:{textBoxevi[textBoxBrojac].Text}";
                            else unosDictionary["atributi"] = $"{controlInfoLista[i].tekst}:{textBoxevi[textBoxBrojac].Text}";
                        }
                        else unosDictionary[controlInfoLista[i].tekst] = textBoxevi[textBoxBrojac].Text;

                        textBoxBrojac++;
                        break;
                    case "trueFalse":
                        if (controlInfoLista[i].atribut)
                        {
                            if (unosDictionary.ContainsKey("atributi")) unosDictionary["atributi"] += $"|{controlInfoLista[i].tekst}:{comboBoxevi[comboBoxBrojac].SelectedItem.ToString()}";
                            else unosDictionary["atributi"] = $"{controlInfoLista[i].tekst}:{comboBoxevi[comboBoxBrojac].SelectedItem.ToString()}";
                        }
                        else unosDictionary[controlInfoLista[i].tekst] = comboBoxevi[comboBoxBrojac].SelectedItem.ToString();
                        comboBoxBrojac++;
                        break;
                    case "proizvodjac":
                        unosDictionary[controlInfoLista[i].tekst] = (comboBoxevi[comboBoxBrojac].SelectedItem as ComboboxItem).Value;
                        
                        comboBoxBrojac++;
                        break;
                    case "slika":
                        string sveSlikeZajednoString = "nemaslike";
                        for(int j=0;j<filesToUpload.Count;j++)
                        {
                            
                            if (j != 0) { sveSlikeZajednoString += "|"; }
                            else { sveSlikeZajednoString = ""; }
                            sveSlikeZajednoString += System.IO.Path.GetFileName(filesToUpload[j]);
                        }
                        unosDictionary[controlInfoLista[i].tekst] = sveSlikeZajednoString;
                        break;
                }
            }
            if(unesenoSve!=false)
            {
                try
                {
                    //Transferujemo ceo unos u dinamicni objekat koji mozemo da koristimo kada zovemo sql komande
                    dynamic unos = unosDictionary;
                    //Pravimo dinamicnu sql naredbu 
                    string sql = "Insert into " + this.tip + "(" + sveKolone + ")VALUES(" + sveKoloneParametri + ")";
                    //Izvrsavamo dinamicnu naredbu
                    await dataAccess.SaveDataAsync<dynamic>(sql, unos, Helper.CnnVal("LukaKomp"));

                    //Posle toga nakon sto smo se uverili da su nam stvari insertovane uploadujemo slike
                    for (int j = 0; j < filesToUpload.Count; j++)
                    {
                        string fileNameTemp = System.IO.Path.GetFileName(filesToUpload[j]);
                        while (System.IO.File.Exists(folderPathImage + fileNameTemp))
                        {
                            int random = new Random().Next(10000, 100000000);
                            fileNameTemp = random + System.IO.Path.GetExtension(filesToUpload[j]);
                        }
                        System.IO.File.Copy(filesToUpload[j], folderPathImage + fileNameTemp, true);
                    }
                    //System.IO.File.Copy(file.FileNames[i], folderPathImage + fileNameTemp, true);
                    MessageBox.Show("Uspesno!");
                    this.Close();
                }
                catch (Exception ex)
                {
                    MessageBox.Show("Greska"+ex);
                }
            }
            else
            {
                MessageBox.Show("Niste uneli sve!"); 
            }
        }

        public void dodajLabel(int labelBrojac, int top, int left, int controlInfoBrojac)
        {
            labeli.Add(new Label());
            this.Controls.Add(labeli[labelBrojac]);
            labeli[labelBrojac].Top = top;
            labeli[labelBrojac].ForeColor = Color.Black;
            labeli[labelBrojac].Left = left;
            labeli[labelBrojac].Size = new System.Drawing.Size(200, 17);
            labeli[labelBrojac].Text = controlInfoLista[labelBrojac].tekst;
        }
        public void dodajTextBox(int textBoxBrojac, int top, int left)
        {
            textBoxevi.Add(new TextBox());
            this.Controls.Add(textBoxevi[textBoxBrojac]);
            textBoxevi[textBoxBrojac].Top = top;
            textBoxevi[textBoxBrojac].Left = left;
            textBoxevi[textBoxBrojac].Size = new System.Drawing.Size(318, 23);
        }

        //Postavljamo kontrole na ekran
        public void instancirajKomponente()
        {
            int comboBoxBrojac = 0;
            int labelBrojac = 0;
            int textBoxBrojac = 0;
            int pictureBoxBrojac = 0;
            int buttonBrojac = 0;
            int top = 20,left = 15;

            //Naslov
            this.Controls.Add(naslov);
            naslov.Top = top;
            naslov.ForeColor = Color.Black;
            naslov.Left = left;
            naslov.Font = new Font("Arial", 25, FontStyle.Bold);
            naslov.Size = new System.Drawing.Size(1000, 50);
            naslov.Text = "Dodaj "+ gramatickiIspravanTip;
            top += 50;
            
            for (int i=0;i<controlInfoLista.Count;i++)
            {
                dodajLabel(labelBrojac, top, left, i);
                labelBrojac++;
                top += 17;
                switch (controlInfoLista[i].tip)
                {
                    case "textBox":
                        dodajTextBox(textBoxBrojac, top, left);
                        textBoxBrojac++;
                        textBoxBrojac++;
                        top += 23;
                        break;
                    case "textBoxBroj":
                        dodajTextBox(textBoxBrojac, top, left);
                        //Preventujemo unos Brojeva
                        textBoxevi[textBoxBrojac].KeyPress += keyPressBroj;
                        textBoxBrojac++;
                        top += 23;
                        break;
                    case "richTextBox":
                        dodajTextBox(textBoxBrojac, top, left);
                        textBoxevi[textBoxBrojac].ScrollBars = ScrollBars.Vertical;
                        textBoxevi[textBoxBrojac].Multiline = true;
                        textBoxevi[textBoxBrojac].Size = new System.Drawing.Size(318, 90);
                        textBoxBrojac++;
                        top += 100;
                        break;
                    case "slika":
                        pictureBoxevi.Add(new PictureBox());
                        this.Controls.Add(pictureBoxevi[pictureBoxBrojac]);
                        pictureBoxevi[pictureBoxBrojac].Top = top;
                        pictureBoxevi[pictureBoxBrojac].Left = left;
                        pictureBoxevi[pictureBoxBrojac].Size = new System.Drawing.Size(40, 40);
                        pictureBoxevi[pictureBoxBrojac].SizeMode = PictureBoxSizeMode.StretchImage;
                        pictureBoxBrojac++;
                        //-----------------------------------------------------------------------------
                        dugmici.Add(new Button());
                        this.Controls.Add(dugmici[buttonBrojac]);
                        dugmici[buttonBrojac].Top = top;
                        dugmici[buttonBrojac].Left = left + (318-150);
                        dugmici[buttonBrojac].Size = new System.Drawing.Size(150, 40);
                        dugmici[buttonBrojac].Click += (sender, EventArgs) => { dugmeImageKliknuto(sender, EventArgs, (pictureBoxBrojac-1)); };
                        dugmici[buttonBrojac].Text = "Dodaj sliku";
                        buttonBrojac++;
                        top += 42;
                        break;
                    case "proizvodjac":
                        loadProizvodjac(comboBoxBrojac, top, left);
                        comboBoxBrojac++;
                        top += 25;
                        break;
                    case "trueFalse":
                        comboBoxevi.Add(new ComboBox());
                        this.Controls.Add(comboBoxevi[comboBoxBrojac]);
                        comboBoxevi[comboBoxBrojac].Top = top;
                        comboBoxevi[comboBoxBrojac].Left = left;
                        comboBoxevi[comboBoxBrojac].Size = new System.Drawing.Size(318, 23);
                        comboBoxevi[comboBoxBrojac].DropDownStyle = ComboBoxStyle.DropDownList;
                        comboBoxevi[comboBoxBrojac].Items.Add("True");
                        comboBoxevi[comboBoxBrojac].Items.Add("False");
                        comboBoxevi[comboBoxBrojac].SelectedIndex = 0;
                        comboBoxBrojac++;
                        top += 25;
                        break;
                    default:MessageBox.Show(controlInfoLista[i].tip);break;
                }
            }
            //Kreiraj submit button
            dugmici.Add(new Button());
            this.Controls.Add(dugmici[buttonBrojac]);
            dugmici[buttonBrojac].Top = top+10;
            dugmici[buttonBrojac].Left = left + (110);
            dugmici[buttonBrojac].Size = new System.Drawing.Size(90, 40);
            dugmici[buttonBrojac].Click += dugmeSubmitKliknuto;
            dugmici[buttonBrojac].Text = "Submit";
            top += 52;
            
            //Menjamo velicinu prozora u zavisnosti od toga koliko komponenti imamo
            this.Size = new System.Drawing.Size(this.Size.Width, top+5); 
        }

        //Ucitavamo proizvodjace iz baze podataka 
        private async void loadProizvodjac(int comboBoxBrojac,int top,int left)
        {
            comboBoxevi.Add(new ComboBox());
            this.Controls.Add(comboBoxevi[comboBoxBrojac]);
            comboBoxevi[comboBoxBrojac].Top = top;
            comboBoxevi[comboBoxBrojac].Left = left;
            comboBoxevi[comboBoxBrojac].Size = new System.Drawing.Size(318, 23);
            comboBoxevi[comboBoxBrojac].DropDownStyle = ComboBoxStyle.DropDownList;

            List<Proizvodjac> proizvodjaci = new List<Proizvodjac>();
            proizvodjaci = await dataAccess.LoadDataAsync<Proizvodjac, dynamic>(
                "select * from proizvodjac",
                new {  },
                Helper.CnnVal("LukaKomp"));
            
            for(int i=0;i<proizvodjaci.Count;i++)
            {
                //Smestamo stvari koje fetchujemo u objekat kako bi smo mogli da dobijemo id od selektovanog proizvodajca
                ComboboxItem item = new ComboboxItem();
                item.Text = proizvodjaci[i].ime;
                item.Value = proizvodjaci[i].id;
                comboBoxevi[comboBoxBrojac].Items.Add(item);
            }
            comboBoxevi[comboBoxBrojac].SelectedIndex = 0;
        }

        //Fetchujemo slike koje su postavljene u openfiledialogu
        private void dugmeImageKliknuto(object sender, EventArgs e,int pictureBoxId)
        {
            filesToUpload.Clear();
            OpenFileDialog file = new OpenFileDialog();
            file.Filter = "Image files(*.jpg; *.jpeg; *.gif; *.bmp; *.png;)|*.jpg; *.jpeg; *.gif; *.bmp; *.png;";
            file.Multiselect = true;
            if (file.ShowDialog() == DialogResult.OK)
            {
                //pictureBoxevi[pictureBoxId].Image = new Bitmap(file.FileName);

                for (int i = 0; i < file.FileNames.Length; i++)
                {
                    //Dodajemo samo prvu sliku u pictureBox
                    if (i == 0) { pictureBoxevi[pictureBoxId].Image = new Bitmap(file.FileNames[i]); }
                    //Dodajemo u listu koje fileove treba da uploadujemo pri submitu
                    filesToUpload.Add(file.FileNames[i]);
                }
            }

            file.Dispose();
        }
        //Preventujemo unos Brojeva
        private void keyPressBroj(object sender, KeyPressEventArgs e)
        {
            if(!char.IsControl(e.KeyChar) && !char.IsDigit(e.KeyChar))
            {
                e.Handled = true;
            }
        }


        Point lastPoint;
        private void DodajKomponentu_MouseMove(object sender, MouseEventArgs e)
        {
            if (e.Button == MouseButtons.Left)
            {
                this.Left += e.X - lastPoint.X;
                this.Top += e.Y - lastPoint.Y;
            }
        }

        private void DodajKomponentu_MouseDown(object sender, MouseEventArgs e)
        {
            lastPoint = new Point(e.X, e.Y);
        }

        private void exitButton_Click(object sender, EventArgs e)
        {
            this.Close();
        }
    }
}
