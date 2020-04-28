#include <stdio.h>
#include <string.h>
#include <iostream>
#include <fstream>
#include <sstream>
#include <algorithm>
#include <windows.h>
#include <conio.h>
#include<cstdlib>
#include<ctime>

#include "rapidxml-1.13/rapidxml.hpp"
#include "rapidxml-1.13/rapidxml_print.hpp"
#include "rapidxml-1.13/rapidxml_utils.hpp"
#include "wyp.hpp"
#include <vector>

using namespace rapidxml;
using namespace std;

HANDLE hConsole = GetStdHandle(STD_OUTPUT_HANDLE);

char * get_attrvalue(xml_node<>* N, char * text)
{
   xml_attribute<> * A = N->first_attribute(text);
   if (A) return A->value();

   cout << "problem 4";
   exit(10);
   return "????";
}

string IntToString (int a)
{
   ostringstream temp;
   temp<<a;
   return temp.str();
}

string FloatToString (float a)
{
   ostringstream temp;
   temp<<a;
   return temp.str();
}

bool pobierz(xml_document <> *doc,char * Nazwa, char * rozszezenie,vector<char> ** buffer)
{
   string nazwa_doc = string(Nazwa) + string(rozszezenie);
   char *cstr = new char[nazwa_doc.length() + 1];
   strcpy(cstr, nazwa_doc.c_str());

   ifstream XmlF (cstr);
   if (!XmlF.good()){cout << "file no exists"; exit(1);}
	vector<char> *B =new vector<char>((istreambuf_iterator<char>(XmlF)), istreambuf_iterator<char>());
	B->push_back('\0');
	try{
   doc->parse<0>(&(*B)[0]);
	}
     catch( parse_error p )
    {
        cout <<"|" <<p.what()<<"|";
        cout << p.where<char>();
        exit(1);
        return false;
    }
    (*buffer) = B;
    delete cstr;
    return true;
}



/// class MojXML

MojXML::MojXML(char * nazwa,char * rozszezenie)
{
   XML = new xml_document<>();
   if (!pobierz(XML,nazwa,rozszezenie,&XMLvect))
   {cout << "Unable to open " <<  "cos" << ".xml";exit(1);}
}

MojXML::~MojXML()
{
   XML->clear();
   delete XML;
   delete XMLvect;
}

xml_node <>* MojXML::utworz_wierzcholek(string name)
{
   char *Name = XML->allocate_string(name.c_str());
   return XML->allocate_node( node_element,Name);
}

void MojXML::dodaj_atrybut(xml_node<>* W,string attr, string value)
{
   char *Attr = XML->allocate_string(attr.c_str());
   char *Value = XML->allocate_string(value.c_str());
   W->append_attribute(XML->allocate_attribute(Attr,Value));
}

void MojXML::zmien_atrybut(xml_attribute<> * A, string value)
{
   char *Value = XML->allocate_string(value.c_str());
   A->value(Value);
}

void MojXML::Zapisz_do_pliku(char * nazwa,char * rozszezenie)
{
   string nazwa_doc = string(nazwa) + string(rozszezenie);
   char *cstr = new char[nazwa_doc.length() + 1];
   strcpy(cstr, nazwa_doc.c_str());
   ofstream File;
   File.open(cstr);
   File << *XML;
   File.flush();
   File.close();
   delete cstr;
}

void MojXML::drukuj()
{
   xml_node<>* temp = XML->first_node();
   if (!temp) {cout << 1;exit(1);}
   temp = temp->first_node();
   while (temp)
   {
      cout << * temp;
      temp = temp->next_sibling();
   }
}



/// class node
node::node(int ID){id=ID;set_prev(NULL);set_next(NULL);}

node *  node::get_prev(){return prev;}

node *  node::get_next(){return next;}

void node::set_prev(node * NewPrev){prev=NewPrev;}

void node::set_next(node * NewNext){next=NewNext;}

void node::print(){cout << "node:" << id <<endl;}

node::~node(){/*cout <<"kasowanie node"<<endl;*/}

string node::get_name(){return "node";}


/// class pojazd
pojazd::pojazd(xml_node<>* W):node(0)
{
   xml_attribute<>* ID = W->first_attribute("id");
   if (!ID) {cout << "problem id"; exit(1);}
   id = atoi(ID->value());
   xml_attribute<>* Marka = W->first_attribute("marka");
   if (!Marka) {cout << "problem marka"; exit(1);}
   marka = Marka->value();
   xml_attribute<>* Model = W->first_attribute("model");
   if (!Model) {cout << "problem model"; exit(1);}
   model = Model->value();
   xml_attribute<>* Kolor = W->first_attribute("kolor");
   if (!Kolor) {cout << "problem kolor"; exit(1);}
   kolor = Kolor->value();
   xml_attribute<>* Skrzynia = W->first_attribute("skrzynia");
   if (!Skrzynia) {cout << "problem skrzynia"; exit(1);}
   skrzynia = Skrzynia->value();
   xml_attribute<>* Paliwo = W->first_attribute("paliwo");
   if (!Paliwo) {cout << "problem paliwo"; exit(1);}
   paliwo = Paliwo->value();
   xml_attribute<>* Rok = W->first_attribute("rok");
   if (!Rok) {cout << "problem rok prod"; exit(1);}
   rok_prod = atoi(Rok->value());
   xml_attribute<>* Moc = W->first_attribute("moc");
   if (!Moc) {cout << "problem moc"; exit(1);}
   moc = atoi(Moc->value());
   xml_attribute<>* Poj = W->first_attribute("poj");
   if (!Poj) {cout << "problem poj"; exit(1);}
   pojemnosc = atof(Poj->value());

   W=W->first_node();

   xml_attribute<>* Wiek = W->first_attribute("wiek");
   if (!Wiek) {cout << "problem Wiek"; exit(1);}
   wiek = atoi(Wiek->value());
   Wymagania=W;

   W=W->next_sibling();

   xml_attribute<>* Rez = W->first_attribute("id_os");
   if (!Rez) {cout << "problem rezerwacja"; exit(1);}
   rezerwacja = atoi(Rez->value());
   Rezerwacja=W;

   W=W->next_sibling();

   xml_attribute<>* Wartosc = W->first_attribute("wartosc");
   if (!Wartosc) {cout << "problem Wartosc-ocena"; exit(1);}
   ocena = atof(Wartosc->value());
   xml_attribute<>* Ilosc = W->first_attribute("ilosc");
   if (!Ilosc) {cout << "problem Ilosc-ocena"; exit(1);}
   ile_ocen = atoi(Ilosc->value());
   Ocena=W;

   W=W->next_sibling();

   xml_attribute<>* Ilosc2 = W->first_attribute("ilosc");
   if (!Ilosc2) {cout << "problem ilosc-wyp"; exit(1);}
   ile_kom = atoi(Ilosc2->value());
   xml_attribute<>* Teraz = W->first_attribute("teraz_id");
   if (!Teraz) {cout << "problem teraz-wyp"; exit(1);}
   wypozyczony = atoi(Teraz->value());
   Wypozyczenia=W;

   W=W->next_sibling();

   xml_attribute<>* Ilosc3 = W->first_attribute("ilosc");
   if (!Ilosc3) {cout << "problem ilosc-kom"; exit(1);}
   ile_kom = atoi(Ilosc3->value());
   Komentarze=W;
}

void pojazd::print()
{
   cout << endl;
   cout << get_name() << endl;
   cout << "id: " << id << endl;
   cout << "marka: " << marka << endl;
   cout << "model: " << model << endl;
   cout << "rok prod: " << rok_prod << endl;
   cout << "paliwo: " << paliwo << endl;
   cout << "skrzynia: " << skrzynia << endl;
   cout << "kolor: " << kolor << endl;
   zapisz_cos_jescze();
   cout << "wymagany wiek: " << wiek << endl;
   cout << "ilosc ocen: " << ile_ocen << " ocena: " << ocena << endl;
   cout << "czy dostepny: " << czy_dostepny() <<endl;
   cout << "liczba komentarzy: " << ile_kom <<endl;
   cout << "  Komentarze:"<<endl;
   for(xml_node<>* kom = Komentarze->first_node();kom;kom=kom->next_sibling())
   {
      xml_attribute<>* k = kom->first_attribute("nick");
      if (k) cout <<"   " << "nick: " << k->value();
      xml_attribute<>* t = kom->first_attribute("tresc");
      if (t) cout << "  " << "tresc: " << t->value()<<endl;
   }
   cout << endl;
}

void pojazd::print_admin()
{
   cout << endl;
   cout << get_name() << endl;
   cout << "id: " << id << endl;
   cout << "marka: " << marka << endl;
   cout << "model: " << model << endl;
   cout << "rok prod: " << rok_prod << endl;
   cout << "paliwo: " << paliwo << endl;
   cout << "skrzynia: " << skrzynia << endl;
   cout << "kolor: " << kolor << endl;
   zapisz_cos_jescze();
   cout << "wymagany wiek: " << wiek << endl;
   cout << "ilosc ocen: " << ile_ocen << " ocena: " << ocena << endl;
   cout << "czy dostepny: "<< wypozyczony << endl;
   cout << "liczba komentarzy: " << ile_kom <<endl;
   cout << "  Komentarze:"<<endl;
   for(xml_node<>* kom = Komentarze->first_node();kom;kom=kom->next_sibling())
   {
      xml_attribute<>* k = kom->first_attribute("nick");
      if (k) cout <<"   " << "nick: " << k->value();
      xml_attribute<>* t = kom->first_attribute("tresc");
      if (t) cout << "  " << "tresc: " << t->value()<<endl;
   }
   cout << endl;
}

string pojazd::czy_dostepny()
{
   if(wypozyczony!=0) return "NIE";
   else return "TAK";
}

string pojazd::get_name(){return "pojazd";}



/// class osobowy
osobowy::osobowy(xml_node<>* W):pojazd(W)
{
   xml_attribute<>* Miej = W->first_attribute("ile_miejsc");
   if (!Miej) {cout << "problem miejsca"; exit(1);}
   miejsca = atoi(Miej->value());
}

void osobowy::zapisz_cos_jescze()
{
   cout << "miejsca: " << miejsca << endl;
}

void pojazd::print2()
{
   cout << endl;
   cout << get_name() << endl;
   cout << "id: " << id << endl;
   cout << "marka: " << marka << endl;
   cout << "model: " << model << endl;
   cout << "rok prod: " << rok_prod << endl;
   cout << "skrzynia: " << skrzynia << endl;
   cout << "kolor: " << kolor << endl;
}

string osobowy::get_name(){return "osobowy";}



/// class dostawczy
dostawczy::dostawczy(xml_node<>* W):pojazd(W)
{
   xml_attribute<>* Ladownosc = W->first_attribute("ladownosc");
   if (!Ladownosc) {cout << "problem ladownosc"; exit(1);}
   ladownosc = atoi(Ladownosc->value());
}

void dostawczy::zapisz_cos_jescze()
{
   cout << "ladownosc: " << ladownosc << " tony" << endl;
}

string dostawczy::get_name(){return "dostawczy";}



/// class motor
motor::motor(xml_node<>* W):pojazd(W)
{
   xml_attribute<>* Typ = W->first_attribute("typ");
   if (!Typ) {cout << "problem typ"; exit(1);}
   typ = Typ->value();
}

void motor::zapisz_cos_jescze()
{

   cout << "typ: " << typ << endl;
}

string motor::get_name(){return "motor";}



/// class osoba
osoba::osoba(xml_node<>* W):node(0)
{
   Dane=W;
   xml_attribute<>* ID = W->first_attribute("id");
   if (!ID) {cout << "problem id"; exit(1);}
   id = atoi(ID->value());
   xml_attribute<>* Nick = W->first_attribute("nick");
   if (!Nick) {cout << "problem nick"; exit(1);}
   nick = Nick->value();
   xml_attribute<>* Mail = W->first_attribute("email");
   if (!Mail) {cout << "problem mail"; exit(1);}
   email = Mail->value();
   xml_attribute<>* Imie = W->first_attribute("imie");
   if (!Imie) {cout << "problem imie"; exit(1);}
   imie = Imie->value();
   xml_attribute<>* Nazwisko = W->first_attribute("nazwisko");
   if (!Nazwisko) {cout << "problem nazwisko"; exit(1);}
   nazwisko = Nazwisko->value();
   xml_attribute<>* Haslo = W->first_attribute("haslo");
   if (!Haslo) {cout << "problem haslo"; exit(1);}
   haslo = Haslo->value();
   xml_attribute<>* Wiek = W->first_attribute("wiek");
   if (!Wiek) {cout << "problem wiek"; exit(1);}
   wiek = atoi(Wiek->value());

   W=W->first_node();

   xml_attribute<>* Rez = W->first_attribute("id_sam");
   if (!Rez) {cout << "problem id_sam-os"; exit(1);}
   rez = atoi(Rez->value());
   Rezerwacja=W;

   W=W->next_sibling();

   xml_attribute<>* Wyp = W->first_attribute("ile_wyp");
   if (!Wyp) {cout << "problem ile_wyp-os"; exit(1);}
   ile_wyp = atoi(Wyp->value());
   Wypozyczenia=W;

   W=W->next_sibling();
}

string osoba::get_name(){return "osoba";}

void osoba::print()
{
   cout << endl;
   cout << "nick: " << nick << endl;
   cout << "email: " << email << endl;
   cout << "id: " << id << endl;
   cout << "imie: " << imie << endl;
   cout << "nazwisko: " << nazwisko<< endl;
   cout << "lat: " << wiek << endl;
   //cout << "ile rezerwuje: " << rez << endl;
   cout << "teraz wypozycza : " << ile_wyp << " pojazdow" <<endl;
   cout << endl;
}

void osoba::print_zal()
{
   cout << "id: " << id;
   cout << " nick: " << nick;
   cout << "  " << imie;
   cout << "  " << nazwisko<< endl;

}



/// class list
void list::set_first(node * NewFirst){first=NewFirst;}
void list::set_last(node * NewLast){last=NewLast;}
void list::set_number(int NewNumber){number=NewNumber;}
void list::increase_number(){number++;}
node * list::get_first(){return first;}
node * list::get_last(){return last;}
int list::get_number(){return number;}
list::list()
{
   set_first(NULL);
   set_last(NULL);
   set_number(0);
}
node * list::create_node(int ID)
{
   return new node(ID);
}
void list::Add2End(node * N)
{
   if (get_number()==0)
   {
      set_first(N);
      set_last(N);
   }
   else
   {
      get_last()->set_next(N);
      N->set_prev(get_last());
      set_last(N);
   }
      increase_number();
   }
void list::print()
{
   node * Temp = get_first();
   while (Temp)
   {
      Temp->print();
      cout << endl;
      Temp=Temp->get_next();
   }
}
list::~list()
{
   node * N =get_first(),*Temp;
   while(N)
   {
      Temp=N;
      N=N->get_next();
      delete(Temp);
   }
}


/// calss wypozyczalnia
wypozyczalnia::wypozyczalnia():Dane((char*)"wypozyczalnia",(char*)".xml")
{
   osoby = new list();
   pojazdy = new list();
   cout << * Dane.XML;
   xml_node<> * temp = Dane.XML->first_node();
   if (!temp) {cout << "UUU";exit(1);}
   temp=temp->first_node();
   while(temp)
   {
      if (strcmp(temp->name(),"osoba")==0)
      {
         node * cos = new osoba(temp);
         osoby->Add2End(cos);
      }
      else if (strcmp(temp->name(),(char*)"osobowy")==0)
      {
         node * cos = new osobowy(temp);
         pojazdy->Add2End(cos);
      }
      else if (strcmp(temp->name(),(char*)"dostawczy")==0)
      {
         node * cos = new dostawczy(temp);
         pojazdy->Add2End(cos);
      }
      else if (strcmp(temp->name(),(char*)"motor")==0)
      {
         node * cos = new motor(temp);
         pojazdy->Add2End(cos);
      }
      else {cout << "blad dodawania elemdntu do listy"<<temp->name();exit(1);}
      temp= temp->next_sibling();
      }
      Dane.Zapisz_do_pliku("kopia",".xml");
      wywolaj_start();
}

void wypozyczalnia::dodaj_osobe()
{
   string Tnick, Timie, Tnazwisko, Thaslo, Temail;
   int Twiek, Tprawko;
   bool flag=FALSE;

   cout << "Podaj swoj Nick" << endl;
   cin >> Tnick;
   node *temp = osoby->get_first();
   while(temp && flag==FALSE)
      {
         osoba *o = (osoba*)temp;
         if(Tnick == o->nick)
         {
            flag=TRUE;
         }
         temp=temp->get_next();
      }
      if(flag==TRUE)
      {
         cout <<"Taki nick juz istniej, jesli chcesz stworzyc konto wymysli inny nick i wybierz opcje raz jeszcze"<<endl;
      }

   if(flag==FALSE)
   cout << "Podaj swoj email" << endl;
   cin >> Temail;
   cout << "Podaj swoje Imie" << endl;
   cin >> Timie;
   cout << "Podaj swoje Nazwisko" << endl;
   cin >> Tnazwisko;
   cout << "Podaj swoje Haslo" << endl;
   cin >> Thaslo;
   cout << "Podaj swoj wiek" << endl;
   cin >> Twiek;

   xml_node<> *N;
   xml_node<> *W;
   N=Dane.utworz_wierzcholek("osoba");
   int nr = atoi(get_attrvalue(Dane.XML->first_node(),"osoby"));
   nr++;
   Dane.zmien_atrybut(Dane.XML->first_node()->first_attribute("osoby"),IntToString(nr));
   Dane.dodaj_atrybut(N,"id",IntToString(nr));
   Dane.dodaj_atrybut(N,"nick",Tnick);
   Dane.dodaj_atrybut(N,"email",Temail);
   Dane.dodaj_atrybut(N,"imie",Timie);
   Dane.dodaj_atrybut(N,"nazwisko",Tnazwisko);
   Dane.dodaj_atrybut(N,"haslo",Thaslo);
   Dane.dodaj_atrybut(N,"wiek",IntToString(Twiek));
   Dane.XML->first_node()->append_node(N);
   W=Dane.utworz_wierzcholek("rezerwacja");
   Dane.dodaj_atrybut(W,"id_sam","0");
   Dane.XML->first_node()->last_node()->append_node(W);
   W=Dane.utworz_wierzcholek("wypozycza");
   Dane.dodaj_atrybut(W,"ile_wyp","0");
   Dane.XML->first_node()->last_node()->append_node(W);
   W=Dane.utworz_wierzcholek("komentarze");
   Dane.dodaj_atrybut(W,"ilosc","0");
   Dane.XML->first_node()->last_node()->append_node(W);
   osoby->Add2End(new osoba(N));
   cout << "Udalo sie utworzyc konto" <<endl;
}

void wypozyczalnia::dodaj_pojazd()
{
   string odp, Tmarka, Tmodel, Tkolor, Tskrzynia, Tpaliwo, Ttyp, odp2;
   int Trok_prod, Tmoc, Twiek, Tprawko;
   float Tpoj;
   bool flag=FALSE;

   while(odp!="osobowy" && odp!="dostawczy" && odp!="motor")
   {
      cout << "Co chcesz dodac osobowy/dostawczy/motor" << endl;
      cin >> odp;
   }

   cout << "Podaj marke pojzadu" << endl;
   cin >> Tmarka;
   cout << "Podaj model pojzadu" << endl;
   cin >> Tmodel;
   cout << "Podaj kolor pojazdu" << endl;
   cin >> Tkolor;
   cout << "Podaj typ skrzyni" << endl;
   cin >> Tskrzynia;
   cout << "Podaj rodzaj paliwa" << endl;
   cin >> Tpaliwo;
   cout << "Podaj rok produkcji" << endl;
   cin >> Trok_prod;
   cout << "Podaj moc pojazdu" << endl;
   cin >> Tmoc;
   cout << "Podaj pojemnosc" << endl;
   cin >> Tpoj;
   cout << "Podaj minimalny wiek kierowcy" << endl;
   cin >> Twiek;

   if(odp=="osobowy")
   {
      cout << "Podaj ile miejsc ma samochod" << endl;
      cin >> Ttyp;
      odp2="ile_miejsc";
   }
   if(odp=="dostawczy")
   {
      cout << "Podaj ladownosc auta" << endl;
      cin >> Ttyp;
      odp2="ladownosc";
   }
   if(odp=="motor")
   {
      cout << "Podaj typ motoru(np. skuter)" << endl;
      cin >> Ttyp;
      odp2="typ";
   }

   xml_node<> *N;
   xml_node<> *W;
   N=Dane.utworz_wierzcholek(odp);
   int nr = atoi(get_attrvalue(Dane.XML->first_node(),"pojazdy"));
   nr++;
   Dane.zmien_atrybut(Dane.XML->first_node()->first_attribute("pojazdy"),IntToString(nr));
   Dane.dodaj_atrybut(N,"id",IntToString(nr));
   Dane.dodaj_atrybut(N,"marka",Tmarka);
   Dane.dodaj_atrybut(N,"model",Tmodel);
   Dane.dodaj_atrybut(N,odp2,Ttyp);
   Dane.dodaj_atrybut(N,"kolor",Tkolor);
   Dane.dodaj_atrybut(N,"skrzynia",Tskrzynia);
   Dane.dodaj_atrybut(N,"paliwo",Tpaliwo);
   Dane.dodaj_atrybut(N,"rok",IntToString(Trok_prod));
   Dane.dodaj_atrybut(N,"moc",IntToString(Tmoc));
   Dane.dodaj_atrybut(N,"poj",IntToString(Tpoj));
   Dane.XML->first_node()->append_node(N);
   W=Dane.utworz_wierzcholek("wymagania");
   Dane.dodaj_atrybut(W,"wiek",IntToString(Twiek));
   Dane.XML->first_node()->last_node()->append_node(W);
   W=Dane.utworz_wierzcholek("rezerwacja");
   Dane.dodaj_atrybut(W,"id_os","0");
   Dane.XML->first_node()->last_node()->append_node(W);
   W=Dane.utworz_wierzcholek("ocena");
   Dane.dodaj_atrybut(W,"wartosc","0");
   Dane.dodaj_atrybut(W,"ilosc","0");
   Dane.XML->first_node()->last_node()->append_node(W);
   W=Dane.utworz_wierzcholek("wypozyczenia");
   Dane.dodaj_atrybut(W,"ilosc","0");
   Dane.dodaj_atrybut(W,"teraz_id","0");
   Dane.XML->first_node()->last_node()->append_node(W);
   W=Dane.utworz_wierzcholek("komentarze");
   Dane.dodaj_atrybut(W,"ilosc","0");
   Dane.XML->first_node()->last_node()->append_node(W);

   if(odp=="motor") pojazdy->Add2End(new motor(N));
   else if(odp=="osobowy") pojazdy->Add2End(new osobowy(N));
   else if(odp=="dostawczy") pojazdy->Add2End(new dostawczy(N));
   cout << "Udalo sie dodac pojzad" <<endl;
}

osoba *wypozyczalnia::Logowanie()
{
   string Lnick;
   string Lhaslo;
   cout << "Podaj Nick:" << endl;
   cin >> Lnick;
   if(Lnick=="admin")
   {
      cout << "podaj Haslo:" << endl;
      cin >> Lhaslo;
      if(Lhaslo=="admin")
      {
         wywolaj_admin();
         return NULL;
      }
   }
   node *temp = osoby->get_first();
   while(temp)
   {
      if (temp->get_name()!="osoba") cout <<("uuu"+temp->get_name());
      osoba * o = (osoba*)temp;
      if (Lnick==o->nick)
      {
         cout << "podaj haslo:" << endl;
         cin >> Lhaslo;
         if(Lhaslo==o->haslo)
         {
            return o;
         }
         else
         {
            cout << "Bledne haslo" << endl;
            return NULL;
         }
      }
      temp=temp->get_next();
   }
   cout << "Blad danych" << endl;
   return NULL;
}

void wypozyczalnia::drukuj_pojazdy()
{
   string klasa_pojazdu;
   while(klasa_pojazdu!="osobowy" && klasa_pojazdu!="motor" && klasa_pojazdu!="dostawczy")
   {
      cout << "Jakie pojazy wypisac? osobowy/dostawczy/motor" << endl;
      cin >> klasa_pojazdu;
   }
   node *temp= pojazdy->get_first();
   while(temp)
   {
      if(temp->get_name()==klasa_pojazdu)
         temp->print();
         temp=temp->get_next();
   }
}

void wypozyczalnia::drukuj_pojazdy_admin()
{
   string klasa_pojazdu;
   while(klasa_pojazdu!="osobowy" && klasa_pojazdu!="motor" && klasa_pojazdu!="dostawczy")
   {
      cout << "Jakie pojazy wypisac? osobowy/dostawczy/motor" << endl;
      cin >> klasa_pojazdu;
   }
   node *temp= pojazdy->get_first();
   while(temp)
   {
      pojazd *P=(pojazd*)temp;
      if(P->get_name()==klasa_pojazdu)
         P->print_admin();
         temp=temp->get_next();
   }
}

void wypozyczalnia::wyszukaj_pojazdy()
{
   string klasa_pojazdu, odp, odp2;
   cout << "Co chcesz wyszukac? motor/osobowy/dostawczy" << endl;
   cin >> klasa_pojazdu;

   cout << "Podaj kryteruim marka/model/skrzynia" << endl;
   cin >> odp;
   cout << "Podaj wartosc kryteruim"<< endl;
   cin >> odp2;

   node *temp=pojazdy->get_first();
   while(temp)
   {
      if(klasa_pojazdu==temp->get_name())
      {
      pojazd* P=(pojazd*)temp;

      if(odp=="marka" && odp2==P->marka)
      {
         P->print();
      }
      else if(odp=="model" && odp2==P->model)
      {
         P->print();
      }
      else if(odp=="skrzynia" && odp2==P->skrzynia)
      {
         P->print();
      }
      }
      temp=temp->get_next();
   }
}

void wypozyczalnia::wyszukaj_osobe()
{
   string odp, odp2;
   int i;
   cout << "Po czym szukac? nick/imie/nazwisko" << endl;
   cin >> odp;
   cout << "Poadj wartosc " << odp << endl;
   cin >> odp2;

   node *temp=osoby->get_first();
   while(temp)
   {
      osoba *O=(osoba*)temp;
      if(odp=="imie")
      {
         if(odp2==O->imie)O->print();
      }
      else if(odp=="nazwisko")
      {
         if(odp2==O->nazwisko)O->print();
      }
      else if(odp=="nick")
      {
         if(odp2==O->nick)O->print();
      }
      else cout << "blad kryterium" << endl;
      temp=temp->get_next();
   }

}

void wypozyczalnia::drukuj_moje()
{
   node *temp=pojazdy->get_first();
   while(temp)
   {
      pojazd *O=(pojazd*)temp;
      if(zal->id==O->wypozyczony)
      {
         if(O->get_name()=="motor")
         {
            motor *M=(motor*)O;
            M->print2();
         }
         else if(O->get_name()=="osobowy")
         {
            osobowy *M=(osobowy*)O;
            M->print2();
         }
         else if(O->get_name()=="dostawczy")
         {
            dostawczy *M=(dostawczy*)O;
            M->print2();
         }
      }
      temp=temp->get_next();
   }
}

void wypozyczalnia::co_wyp()
{
   osoby->print();
   int i;
   cout << "Podaj id uzytkownika: " << endl;
   cin >> i;

   node *temp=pojazdy->get_first();
   while(temp)
   {
      pojazd *O=(pojazd*)temp;
      if(i==O->wypozyczony)
      {
         O->print2();
      }
      temp=temp->get_next();
   }
}

void wypozyczalnia::drukuj_dostepne()
{
   string odp;
   cout <<"Podaj co chcesz wydrukowac? osobowy/motor/dostawczy" << endl;
   cin >> odp;

   node *temp=pojazdy->get_first();
   while(temp)
   {
      pojazd *P=(pojazd*)temp;
      if(P->wypozyczony==0 && P->get_name()==odp)
      {
         P->print();
      }
      temp=temp->get_next();
   }
}

void wypozyczalnia::dodaj_komentarz()
{
   int numer;
   string komentarz;
   xml_node<> * temp = Dane.XML->first_node();
   xml_node<> * temp2;
   node *temppoj=pojazdy->get_first();
   drukuj_pojazdy();
   cout << "Podaj id pojazdu do dodania komentarza: ";
   cin >> numer;
   if(numer > atoi(get_attrvalue(temp,"pojazdy")))
   {
      cout << "W wypozyczalni nie ma pojazdu o podanym id " << numer;
   }
   else
   {
      cout << "Podaj tresc komentarza: " << endl;
      setbuf(stdin,NULL);
      getline(cin,komentarz);
      bool flag=FALSE;
      while(temppoj)
      {
         if(numer==temppoj->id)
         {
            pojazd* P=(pojazd*)temppoj;
            temp2=P->Komentarze;
            int nr = atoi(get_attrvalue(temp2,"ilosc"));
            nr++;
            Dane.zmien_atrybut(temp2->first_attribute(),IntToString(nr));
            xml_node<> *N=Dane.utworz_wierzcholek("kom");
            Dane.dodaj_atrybut(N,"tresc",komentarz);
            Dane.dodaj_atrybut(N,"nick",zal->nick);
            temp2->append_node(N);
            flag=TRUE;
         }
         temppoj=temppoj->get_next();
      }

      if(!flag) cout << "Nie udalo sie dodac komentarza";
      else cout << "Udalo sie dodac komentarza";
   }
}

void wypozyczalnia::dodaj_ocene()
{
   int numer;
   float ocena;
   xml_node<> * temp = Dane.XML->first_node();
   xml_node<> * temp2;
   node *temppoj=pojazdy->get_first();
   drukuj_pojazdy();
   cout << "Podaj id pojazdu : ";
   cin >> numer;
   if(numer > atoi(get_attrvalue(temp,"pojazdy")))
   {
      cout << "W wypozyczalni nie ma pojazdu o podanym id " << numer;
   }
   else
   {
      cout << "Podaj wartosc oceny od 1 do 5: " << endl;
      cin >> ocena;
      bool flag=FALSE;
      while(temppoj)
      {
         if(numer==temppoj->id)
         {
            flag = TRUE;
            pojazd* P=(pojazd*)temppoj;
            temp2=P->Ocena;
            int nr = atoi(get_attrvalue(temp2,"ilosc"));
            nr++;
            P->ocena=(P->ocena*P->ile_ocen+ocena)/nr;
            Dane.zmien_atrybut(temp2->first_attribute("ilosc"),IntToString(nr));
            Dane.zmien_atrybut(temp2->first_attribute("wartosc"),FloatToString(P->ocena));
         }
         temppoj=temppoj->get_next();
      }

      if(!flag) cout << "Nie udalo sie dodac oceny";
      else cout << "Udalo sie dodac oceny";
   }
}

void wypozyczalnia::edytuj_dane()
{
   zal->print();
   string odp;
   int odpp;
   cout << "Jakie dane chcesz edytowac?" << endl;
   cin >> odp;
   if(odp=="nick") cout << "Nie mozesz edytowac nicku" <<endl;
   else if(odp=="email")
   {
    cout << "Podaj email" << endl;
    cin >> odp;
    zal->email=odp;
    Dane.zmien_atrybut(zal->Dane->first_attribute("email"),odp);
   }
   else if(odp=="imie")
   {
    cout << "Podaj imie" << endl;
    cin >> odp;
    zal->imie=odp;
    Dane.zmien_atrybut(zal->Dane->first_attribute("imie"),odp);
   }
   else if(odp=="nazwisko")
   {
    cout << "Podaj nazwisko" << endl;
    cin >> odp;
    zal->nazwisko=odp;
    Dane.zmien_atrybut(zal->Dane->first_attribute("nazwisko"),odp);
   }
   else if(odp=="wiek")
   {
    cout << "Podaj swoj wiek" << endl;
    cin >> odpp;
    zal->wiek=odpp;
    Dane.zmien_atrybut(zal->Dane->first_attribute("wiek"),IntToString(odpp));
   }
   else if(odp=="haslo")
   {
   string odp2;
    cout << "Podaj haslo" << endl;
    cin >> odp;
    cout << "Potwierdz haslo" <<endl;
    cin >> odp2;
    if(odp==odp2) zal->haslo=odp;
    Dane.zmien_atrybut(zal->Dane->first_attribute("haslo"),odp);
   }
}

string wypozyczalnia::generator()
{
   srand( time( NULL ) );
   string wylosowane;

   for( int i = 0; i < 8; i++ )
   {
      string los;
      los =(( rand() %( 'z' - 'a' ) ) + 'a' );
      wylosowane = wylosowane + los;


    } //for

   return wylosowane;
}

void wypozyczalnia::reset_hasla()
{
   string Tnick, Temail, Thaslo;
   cout << "Podaj swoj nick:" << endl;
   cin >> Tnick;
   cout << "Podaj swoj emial:" << endl;
   cin >> Temail;

   Thaslo=generator();

   node* temp=osoby->get_first();
   while(temp)
   {
      osoba *O=(osoba*)temp;
      if(O->nick==Tnick && O->email==Temail)
      {
         O->haslo==Thaslo;
         Dane.zmien_atrybut(O->Dane->first_attribute("haslo"),Thaslo);

      }
      temp=temp->get_next();
   }

   cout << Thaslo;
}

void wypozyczalnia::wypozycz()
{
   cout << "Wyszukaj pojazdu ktory chcesz wypozyczyc i zapamietaj ID" << endl;
   drukuj_pojazdy();
   int ID;
   bool flag=FALSE;
   cout << "Podaj id pojazdu ktory chcesz wypozyczyc lub podaj 0 jesli chcesz zrezygnowac" <<endl;
   cin >> ID;

   if(ID!=0)
   {
      {
         node *temp=pojazdy->get_first();
         while(temp)
         {
            pojazd *O=(pojazd*)temp;
            if(ID==O->id && O->wypozyczony==0 && zal->wiek>=O->wiek)
            {
               cout << "test";
               O->wypozyczony=zal->id;
               zal->ile_wyp++;
               flag=TRUE;
               int nr = atoi(get_attrvalue(O->Wypozyczenia,"ilosc"));
               nr++;
               Dane.zmien_atrybut(O->Wypozyczenia->first_attribute("ilosc"),IntToString(nr));
               Dane.zmien_atrybut(O->Wypozyczenia->first_attribute("teraz_id"),IntToString(zal->id));
               xml_node<> *N=O->Wypozyczenia;
               N=Dane.utworz_wierzcholek("wyp");
               Dane.dodaj_atrybut(N,"numer",IntToString(nr));
               Dane.dodaj_atrybut(N,"id_os",IntToString(zal->id));
               Dane.dodaj_atrybut(N,"nick_os",zal->nick);
               Dane.dodaj_atrybut(N,"imie_os",zal->imie);
               Dane.dodaj_atrybut(N,"naz_os",zal->nazwisko);
               O->Wypozyczenia->append_node(N);
               Dane.zmien_atrybut(zal->Wypozyczenia->first_attribute("ile_wyp"),IntToString(zal->ile_wyp));
            }
            temp=temp->get_next();
         }
         }

      if(flag==TRUE) cout << "Udalo sie wypozyczyc" <<endl;
      if(flag==FALSE) cout << "Nie udalo sie wypozyczyc" <<endl ;
   }
}

void wypozyczalnia::oddaj()
{
   if(zal->ile_wyp==0) cout << "Nie masz zadych pojazdow";
   int ID=-1;
   bool flag=FALSE;
   if(ID!=0 && zal->ile_wyp!=0)
   {
      drukuj_moje();
      cout << "Wybierz pojazd ktory chcesz oddac i podaj id lub wpisz 0 jesli rezygnujesz" << endl;
      cin >> ID;

      if(ID!=0)
      {
         node *temp=pojazdy->get_first();
         while(temp)
         {
            pojazd *K=(pojazd*)temp;
            if(ID==K->id && K->wypozyczony==zal->id)
            {
               K->wypozyczony=0;
               zal->ile_wyp--;
               flag=TRUE;
               Dane.zmien_atrybut(K->Wypozyczenia->first_attribute("teraz_id"),IntToString(0));
               Dane.zmien_atrybut(zal->Wypozyczenia->first_attribute("ile_wyp"),IntToString(zal->ile_wyp));
            }
            temp=temp->get_next();
         }
         if(flag==TRUE) cout << "Udalo sie oddac" <<endl;
         if(flag==FALSE) cout << "Nie udalo sie oddac" <<endl ;
      }
   }
}

void wypozyczalnia::historia()
{
   drukuj_pojazdy();
   int i;
   cout << "Historie jakiego pojazdu wydrukowac - podaj id"<< endl;
   cin >> i;

   node *temp=pojazdy->get_first();
   while(temp)
   {
      if(i==temp->id)
      {
         pojazd *P=(pojazd*)temp;
         P->print2();
         cout << " " << "Historia wypozyczen:" << endl;
         for(xml_node<>* kom = P->Wypozyczenia->first_node();kom;kom=kom->next_sibling())
         {
            xml_attribute<>* n = kom->first_attribute("numer");
            if (n) cout <<"   " << n->value() << ". ";
            xml_attribute<>* t = kom->first_attribute("id_os");
            if (t) cout << "  " << "id_os: " << t->value()<< " ";
            xml_attribute<>* ni = kom->first_attribute("nick_os");
            if (ni) cout << "  " << "nick: " << ni->value()<< " ";
            xml_attribute<>* f = kom->first_attribute("imie_os");
            if (f) cout << "  " << "imie: " << f->value()<< " ";
            xml_attribute<>* nz = kom->first_attribute("naz_os");
            if (nz) cout << "  " << "id_os: " << nz->value()<< " ";
            cout << endl;
         }
      }
      temp=temp->get_next();
   }
}

void wypozyczalnia::printMenu(bool zielone,string S)
{
   if (zielone) SetConsoleTextAttribute(hConsole, 10);
   else  SetConsoleTextAttribute(hConsole, 7);
   cout << S<<endl;
   SetConsoleTextAttribute(hConsole, 10);
}

void wypozyczalnia::print_Menu_start(int i)
{
   cout << "      WYPOZYCZALNIA POJAZDOW" << endl;
   cout << "----------------------------------" << endl;
   printMenu(i==1,"1. Drukuj pojazdy");
   printMenu(i==2,"2. Wyszukaj pojazd");
   printMenu(i==3,"3. Zaloguj");
   printMenu(i==4,"4. Utworz konto");
   printMenu(i==0,"0. Wyjscie");
}

void wypozyczalnia::print_Menu_zalogowany(int i)
{
   cout << "      WYPOZYCZALNIA POJAZDOW" << endl;
   cout << "----------------------------------" << endl;
   zal->print_zal();
   printMenu(i==1,"1. Drukuj pojazdy");
   printMenu(i==2,"2. Drukuj dostepne pojazdy");
   printMenu(i==3,"3. Wyszukaj pojazd");
   printMenu(i==4,"4. Wypozycz pojazd");
   printMenu(i==5,"5. Oddaj pojazd");
   printMenu(i==6,"6. Drukuj wypozyczone");
   printMenu(i==7,"7. Pokaz pelne dane");
   printMenu(i==8,"8. Edytuj dane");
   printMenu(i==9,"9. Dodaj komentazrz");
   printMenu(i==10,"10. Dodaj ocene");
   printMenu(i==0,"0. Wyloguj");
}

void wypozyczalnia::print_Menu_admin(int i)
{
   cout << "      WYPOZYCZALNIA POJAZDOW" << endl;
   cout << "----------------------------------" << endl;
   printMenu(i==1,"1. Drukuj pojazdy");
   printMenu(i==2,"2. Drukuj osoby");
   printMenu(i==3,"3. Zapisz do pliku");
   printMenu(i==4,"4. Dodaj pojazd");
   printMenu(i==5,"5. Dodaj osobe");
   printMenu(i==6,"6. Wyszukaj pojazd");
   printMenu(i==7,"7. Drukuj dostepne");
   printMenu(i==8,"8. Wyszukaj osobe");
   printMenu(i==9,"9. Historia wypozyczen");
   printMenu(i==10,"10. Co wypozycza osoba");
   printMenu(i==0,"0. Wyloguj");
}

void wypozyczalnia::wywolaj_start()
{
   int menu_nr=1;
   while (menu_nr!=0)
   {
      menu_nr=0;
      system("CLS");
      print_Menu_start(menu_nr);
      int OK=0;
      while (OK==0)
      {
         if (kbhit())
         {
            int c = getch();
            //printf("%d",c);
            if (c==224)
            {
               c = getch();
               switch (c)
               {
                  case 72:
                  {
                     menu_nr--;
                     if (menu_nr <0)menu_nr=4;
                     system("CLS");
                     print_Menu_start(menu_nr);
                  }
                  break;
                  case 80:
                  {
                     menu_nr++;
                     if (menu_nr >4)menu_nr=0;
                     system("CLS");
                     print_Menu_start(menu_nr);
                  }
                  break;
               }
            }
            else if (c==13) OK=1;
         };
      };
      switch (menu_nr)
      {
         case 0: break;
         case 1: drukuj_pojazdy();break;
         case 2: wyszukaj_pojazdy();break;
         case 3: zal=Logowanie();
                 if(zal!=NULL) wywolaj_zalogowany();break;
         case 4: dodaj_osobe(); break;
                 cout << "Konto utworzone. Zapamietaj swoj nick i haslo!" << endl; break;
         //case 4: dodaj_osobe();
         default:
            cout << "Bledna opcja" << menu_nr;
      }
      system("PAUSE");
   }
}

void wypozyczalnia::wywolaj_zalogowany()
{
   int menu_nr=1;
   while (menu_nr!=0)
   {
      menu_nr=0;
      system("CLS");
      print_Menu_zalogowany(menu_nr);
      int OK=0;
      while (OK==0)
      {
         if (kbhit())
         {
            int c = getch();
            //printf("%d",c);
            if (c==224)
            {
               c = getch();
               switch (c)
               {
                  case 72:
                  {
                     menu_nr--;
                     if (menu_nr <0)menu_nr=10;
                     system("CLS");
                     print_Menu_zalogowany(menu_nr);
                  }
                  break;
                  case 80:
                  {
                     menu_nr++;
                     if (menu_nr >10)menu_nr=0;
                     system("CLS");
                     print_Menu_zalogowany(menu_nr);
                  }
                  break;
               }
            }
            else if (c==13) OK=1;
         }
      }
      switch (menu_nr)
      {
         case 0: Dane.Zapisz_do_pliku("wypozyczalnia",".xml");
                 zal=NULL;break;
         case 1: drukuj_pojazdy();break;
         case 2: drukuj_dostepne();break;
         case 3: wyszukaj_pojazdy();break;
         case 4: wypozycz();break;
         case 5: oddaj();break;
         case 6: drukuj_moje();break;
         case 7: zal->print();break;
         case 8: edytuj_dane();break;
         case 9: dodaj_komentarz();break;
         case 10: dodaj_ocene();break;
         default:
            cout << "Bledna opcja" << menu_nr;
      }
      system("PAUSE");
   }
}

void wypozyczalnia::wywolaj_admin()
{
   int menu_nr=1;
   while (menu_nr!=0)
   {
      menu_nr=0;
      system("CLS");
      print_Menu_admin(menu_nr);
      int OK=0;
      while (OK==0)
      {
         if (kbhit())
         {
            int c = getch();
            //printf("%d",c);
            if (c==224)
            {
               c = getch();
               switch (c)
               {
                  case 72:
                  {
                     menu_nr--;
                     if (menu_nr <0)menu_nr=10;
                     system("CLS");
                     print_Menu_admin(menu_nr);
                  }
                  break;
                  case 80:
                  {
                     menu_nr++;
                     if (menu_nr >10)menu_nr=0;
                     system("CLS");
                     print_Menu_admin(menu_nr);
                  }
                  break;
               }
            }
            else if (c==13) OK=1;
         }
      }
      switch (menu_nr)
      {
         case 0: break;
         case 1: drukuj_pojazdy_admin();break;
         case 2: osoby->print();break;
         case 3: Dane.Zapisz_do_pliku("wypozyczalnia",".xml");break;
         case 4: dodaj_pojazd();break;
         case 5: dodaj_osobe();break;
         case 6: wyszukaj_pojazdy();break;
         case 7: drukuj_dostepne();break;
         case 8: wyszukaj_osobe();break;
         case 9: historia();break;
         case 10: co_wyp();break;
         default:
            cout << "Bledna opcja" << menu_nr;
      }
      system("PAUSE");
   }
}

