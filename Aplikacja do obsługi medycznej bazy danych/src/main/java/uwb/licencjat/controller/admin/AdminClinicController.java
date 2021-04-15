package uwb.licencjat.controller.admin;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;
import uwb.licencjat.exception.ClinicCategoryNotFoundException;
import uwb.licencjat.exception.ClinicNotFoundException;
import uwb.licencjat.model.Clinic;
import uwb.licencjat.service.ClinicCategoryService;
import uwb.licencjat.service.ClinicService;

import javax.validation.Valid;

@Controller
@RequestMapping("admin/clinic")
public class AdminClinicController {

    private ClinicService clinicService;
    private ClinicCategoryService clinicCategoryService;

    @Autowired
    public AdminClinicController(ClinicService clinicService, ClinicCategoryService clinicCategoryService) {
        this.clinicService = clinicService;
        this.clinicCategoryService = clinicCategoryService;
    }

    @GetMapping()
    public String getClinics(@RequestParam(defaultValue = "") String value,
                             @RequestParam(defaultValue = "name") String sort,
                             @RequestParam(defaultValue = "asc") String direction, Model model){
        model.addAttribute("clinicList", clinicService.getAllByValueSorted(value, sort, direction));
        return "admin/clinics";
    }

    @GetMapping("/{id}")
    public String getClinicById(@PathVariable() long id, Model model, RedirectAttributes attributes){
        try{
            model.addAttribute("clinic", clinicService.findById(id));
            return "admin/clinic";
        }catch (ClinicNotFoundException e) {
            attributes.addFlashAttribute("errorMessage", "Nie znaleźiono kliniki o podanym id: " + id);
            return "redirect:/admin";
        }
    }

    @GetMapping("/add")
    public String addClinic(Model model){
        model.addAttribute("clinic", new Clinic());
        model.addAttribute("clinicCategoryList", clinicCategoryService.getAllCategories());
        return "admin/clinic_add";
    }

    @PostMapping("/add")
    public String addClinic(@RequestParam("category") String clinicCategory, @Valid @ModelAttribute Clinic clinic,
                            BindingResult result, RedirectAttributes attributes, Model model){
        try {
            clinicService.checkNameUnique(clinic, result);
            clinicService.addCategoryToClinic(clinic, clinicCategory);
            if(result.hasErrors()) {
                model.addAttribute("clinicCategoryList", clinicCategoryService.getAllCategories());
                return "admin/clinic_edit";
            } else{
                clinicService.saveClinic(clinic);
                attributes.addFlashAttribute("successMessage", "Dane placówki zostały zmienione");
            }
        } catch (ClinicCategoryNotFoundException e) {
            attributes.addFlashAttribute("errorMessage", "Nie udało się zmienić danych gdyż nie" +
                    "odnaleźiono wybranej kategoii");
        }
        return "redirect:/admin";
    }

    @GetMapping("/{id}/edit")
    public String editClinic(@PathVariable long id, Model model, RedirectAttributes attributes){
        try{
            model.addAttribute("clinic", clinicService.findById(id));
            model.addAttribute("clinicCategoryList", clinicCategoryService.getAllCategories());
            return "admin/clinic_edit";
        }catch (ClinicNotFoundException e){
            attributes.addFlashAttribute("errorMessage", "Nie znależiono placówki o podanym id: " + id);
            return "redirect:/admin";
        }
    }

    @PostMapping("/{id}/edit")
    public String editClinic(@PathVariable long id, @RequestParam("category") String clinicCategory,
                             @Valid @ModelAttribute Clinic clinic,
                             BindingResult result, RedirectAttributes attributes, Model model) {
        try {
            clinicService.checkNameUnique(id, clinic, result);
            clinicService.addCategoryToClinic(clinic, clinicCategory);
            if(result.hasErrors()) {
                model.addAttribute("clinicCategoryList", clinicCategoryService.getAllCategories());
                return "admin/clinic_edit";
            } else{
                clinicService.saveClinic(clinic);
                attributes.addFlashAttribute("successMessage", "Dane placówki zostały zmienione");
            }
        } catch (ClinicCategoryNotFoundException e) {
            attributes.addFlashAttribute("errorMessage", "Nie udało się zmienić danych gdyż nie" +
                    "odnaleźiono wybranej kategoii");
        }
        return "redirect:/admin";
    }

    @GetMapping("/{id}/delete")
    public String deleteClinic(@PathVariable long id, RedirectAttributes attributes){
        if(clinicService.existById(id)){
            clinicService.deleteById(id);
            attributes.addFlashAttribute("successMessage", "Udało sie usuna klinikę");
        }else
            attributes.addFlashAttribute("errorMessage", "Nie udało się usunąć kliniki o podanym id: " + id);
        return "redirect:/admin";
    }
}
