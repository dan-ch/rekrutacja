package uwb.licencjat.controller.admin;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;
import uwb.licencjat.exception.ClinicCategoryNotFoundException;
import uwb.licencjat.model.ClinicCategory;
import uwb.licencjat.service.ClinicCategoryService;

import javax.validation.Valid;

@Controller
@RequestMapping("admin/clinic-category")
public class AdminClinicCategoryController {

    private ClinicCategoryService clinicCategoryService;

    @Autowired
    public AdminClinicCategoryController(ClinicCategoryService clinicCategoryService) {
        this.clinicCategoryService = clinicCategoryService;
    }

    @GetMapping()
    public String getCliniCategories(@RequestParam(defaultValue = "") String value,
                                     @RequestParam(defaultValue = "asc") String sort, Model model){
        model.addAttribute("categoryList", clinicCategoryService.getAllByValueSorted(value, sort));
        return "admin/categories";
    }

    @GetMapping("/add")
    public String addClinicCategory(Model model){
        model.addAttribute("clinicCategory", new ClinicCategory());
        return "admin/category_add";
    }

    @PostMapping("/add")
    public String addClinicCategory(@Valid @ModelAttribute ClinicCategory clinicCategory, BindingResult result,
                                    RedirectAttributes attributes){
        clinicCategoryService.checkNameUnique(clinicCategory, result);
        if(result.hasErrors()){
            return "admin/category_add";
        }else{
            clinicCategoryService.saveClinicCategory(clinicCategory);
            attributes.addFlashAttribute("successMessage", "Udało się dodać kategorię");
            return "redirect:/admin";
        }
    }

    @GetMapping("/{id}/edit")
    public String editClinicCategory(@PathVariable long id, Model model, RedirectAttributes attributes){
        try{
            model.addAttribute("clinicCategory", clinicCategoryService.findById(id));
            return "admin/category_edit";
        }catch (ClinicCategoryNotFoundException e){
            attributes.addFlashAttribute("errorMessage", "Nie znależiono kategorii o wybranym id: " + id);
            return "redirect:/admin";
        }
    }

    @PostMapping("/{id}/edit")
    public String editClinicCategory(@PathVariable long id, @Valid @ModelAttribute ClinicCategory clinicCategory,
                                     BindingResult result,
                                     RedirectAttributes attributes){
        clinicCategoryService.checkNameUnique(id, clinicCategory, result);
        if(result.hasErrors()){
            return "admin/category_add";
        }else{
            clinicCategory.setId(id);
            clinicCategoryService.saveClinicCategory(clinicCategory);
            attributes.addFlashAttribute("successMessage", "Udało się dodać kategorię");
            return "redirect:/admin";
        }
    }

    @GetMapping("/{id}/delete")
    public String deleteClinicCategory(@PathVariable long id, RedirectAttributes attributes){
        if(clinicCategoryService.existById(id)){
            clinicCategoryService.deleteById(id);
            attributes.addFlashAttribute("successMessage", "Udało sie usunąć kategorię");
        }else
            attributes.addFlashAttribute("errorMessage", "Nie udało się usunąć kategorii o podanym id: " + id);
        return "redirect:/admin";
    }
}
